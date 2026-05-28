<?php

namespace App\Livewire\Admin;

use App\Models\ChatMessage;
use App\Models\CustomerComplaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SuperDashboard extends Component
{
    public $dateRange = 'last_30_days';
    public $selectedOrder = null;

    protected $queryString = ['dateRange'];

    public function getSelectedDaysProperty()
    {
        return match ($this->dateRange) {
            'last_7_days' => 7,
            'last_30_days' => 30,
            'last_90_days' => 90,
            default => 30,
        };
    }

    public function refreshData()
    {
        // This will trigger a re-render
    }

    public function showOrder($orderId)
    {
        $this->selectedOrder = Order::with('items.product')->find($orderId);
        $this->dispatch('open-modal', 'super-order-modal');
    }

    // ========== KPI METHODS (called from view) ==========

    public function totalUsers()
    {
        return User::count();
    }

    public function newUsersThisMonth()
    {
        return User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
    }

    public function totalProducts()
    {
        return Product::count();
    }

    public function lowStockCount()
    {
        return Product::where('stock', '<=', 5)->count();
    }

    public function pendingOrders()
    {
        return Order::where('status', 'pending')->count();
    }

    public function totalRevenue()
    {
        return Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays($this->selectedDays))
            ->sum('total_price');
    }

    // ========== Export Excell ==========
    public function exportTopProducts()
    {
        // Get the same data used in your dashboard table
        $products = $this->topSellingProducts();

        return (new FastExcel($products))->download('top_selling_products.xlsx', function ($product) {
            return [
                'Product Name' => $product->name,
                'SKU' => $product->sku,
                'Total Sold' => $product->total_sold ?? 0,
                'Total Revenue' => '$' . number_format($product->total_revenue ?? 0, 2),
                'Stock Left' => $product->stock,
            ];
        });
    }

    // ========== CHART DATA METHODS ==========

    public function chartLabels()
    {
        $labels = [];
        for ($i = $this->selectedDays - 1; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subDays($i)->format('M d');
        }
        return $labels;
    }

    public function salesData()
    {
        $data = [];
        for ($i = $this->selectedDays - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $total = Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $data[] = round($total, 2);
        }
        return $data;
    }

    public function usersData()
    {
        $data = [];
        for ($i = $this->selectedDays - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = User::whereDate('created_at', $date)->count();
            $data[] = $count;
        }
        return $data;
    }

    // ========== TABLE DATA METHODS ==========

    public function recentOrders()
    {
        return Order::with('user')
            ->latest()
            ->take(5)
            ->get();
    }

    public function criticalProducts()
    {
        $products = Product::with('category')->get();

        return $products->filter(function ($product) {
            $soldLast30Days = Order::whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })->where('created_at', '>=', Carbon::now()->subDays(30))
                ->sum(DB::raw('(SELECT quantity FROM order_items WHERE order_items.product_id = ' . $product->id . ' AND order_items.order_id = orders.id)'));

            $dailyVelocity = $soldLast30Days / 30;
            $runway = $dailyVelocity > 0 ? floor($product->stock / $dailyVelocity) : 999;

            $product->sold_period = $soldLast30Days;

            return $runway < 7 && $product->stock > 0;
        })->sortBy(function ($product) {
            $soldLast30Days = Order::whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })->where('created_at', '>=', Carbon::now()->subDays(30))
                ->sum(DB::raw('(SELECT quantity FROM order_items WHERE order_items.product_id = ' . $product->id . ' AND order_items.order_id = orders.id)'));
            return $product->stock / max(0.01, ($soldLast30Days / 30));
        })->take(5)->values();
    }

    public function mostAskedProducts()
    {
        $startDate = Carbon::now()->subDays($this->selectedDays);

        $products = Product::all(['id', 'name', 'sku']);
        if ($products->isEmpty()) {
            return collect();
        }

        $chatMessages = ChatMessage::where('role', 'user')
            ->where('created_at', '>=', $startDate)
            ->get();

        if ($chatMessages->isEmpty()) {
            return collect();
        }

        $mentionCounts = [];

        foreach ($chatMessages as $message) {
            $foundProducts = $this->extractProductMentions($message->content, $products);

            foreach ($foundProducts as $productId => $productData) {
                $mentionCounts[$productId] = [
                    'name' => $productData['name'],
                    'sku' => $productData['sku'],
                    'mentions' => ($mentionCounts[$productId]['mentions'] ?? 0) + 1,
                ];
            }
        }

        return collect($mentionCounts)
            ->sortByDesc('mentions')
            ->take(5)
            ->values();
    }

    public function recentComplaints()
    {
        $startDate = Carbon::now()->subDays($this->selectedDays);

        $complaintKeywords = [
            'broken',
            'defective',
            'damaged',
            'not working',
            'doesn\'t work',
            'poor quality',
            'disappointed',
            'terrible',
            'awful',
            'worst',
            'complaint',
            'refund',
            'return',
            'exchange',
            'frustrated',
            'angry',
            'unhappy',
            'dissatisfied',
            'issue',
            'problem',
            'shipping',
            'delivery',
            'late',
        ];

        return CustomerComplaint::where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['message', 'created_at'])
            ->map(function ($complaint) {
                return [
                    'message' => $complaint->message,
                    'date' => $complaint->created_at,
                ];
            });
    }

    private function extractProductMentions(string $content, $products)
    {
        $contentLower = strtolower($content);
        $found = [];

        foreach ($products as $product) {
            $name = strtolower($product->name);
            $sku = strtolower($product->sku);

            if (str_contains($contentLower, $name) || str_contains($contentLower, $sku)) {
                $found[$product->id] = [
                    'name' => $product->name,
                    'sku' => $product->sku,
                ];
            }
        }

        return $found;
    }

    private function messageContainsKeywords(string $content, array $keywords)
    {
        $contentLower = strtolower($content);

        foreach ($keywords as $keyword) {
            if (str_contains($contentLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    public function topSellingProducts()
    {
        return Product::whereHas('orderItems', function ($q) {
            // Ensure the product actually has order items within the timeframe
            $q->whereHas('order', function ($oq) {
                $oq->where('created_at', '>=', Carbon::now()->subDays(30));
            });
        })
            ->withSum([
                'orderItems as total_sold' => function ($q) {
                    $q->whereHas('order', function ($oq) {
                        $oq->where('created_at', '>=', Carbon::now()->subDays(30));
                    });
                }
            ], 'quantity')
            ->withSum([
                'orderItems as total_revenue' => function ($q) {
                    $q->whereHas('order', function ($oq) {
                        $oq->where('created_at', '>=', Carbon::now()->subDays(30));
                    });
                }
            ], DB::raw('price * quantity'))
            // ->having('total_sold', '>', 0)  <-- Removed this line
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();
    }

    public function recentUsers()
    {
        return User::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
