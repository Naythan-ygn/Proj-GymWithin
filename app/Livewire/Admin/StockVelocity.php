<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class StockVelocity extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $trackingDays = 30;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    protected function getBaseQuery()
    {
        $timeframe = Carbon::now()->subDays($this->trackingDays);
        $validStatuses = ['shipped', 'completed'];

        $query = Product::with('category')
            ->select('products.*')
            ->addSelect(['sold_period' => function ($subquery) use ($timeframe, $validStatuses) {
                $subquery->selectRaw('COALESCE(SUM(quantity), 0)')
                    ->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereColumn('order_items.product_id', 'products.id')
                    ->where('orders.created_at', '>=', $timeframe)
                    ->whereIn('orders.status', $validStatuses);
            }]);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('products.name', 'like', '%' . $this->search . '%')
                    ->orWhere('products.sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->categoryFilter)
                    ->orWhere('name', $this->categoryFilter);
            });
        }

        return $query;
    }

    public function exportXLSX()
    {
        // 1. Get the filtered data
        $products = $this->getBaseQuery()->get();
        $filename = 'stock-velocity-report-' . now()->format('Y-m-d') . '.xlsx';

        // 2. Map the data into clean arrays with your Excel Headers as the keys
        $exportData = $products->map(function ($product) {
            $velocity = $product->sold_period / $this->trackingDays;

            // Calculate Runway and Status
            if ($product->stock <= 0) {
                $runway = 0;
                $status = 'Out of Stock';
            } elseif ($velocity == 0) {
                $runway = 'Infinite';
                $status = 'Dead Stock Risk';
            } else {
                $runway = floor($product->stock / $velocity);
                $status = $runway <= 7 ? 'Critical' : ($runway <= 30 ? 'Warning' : 'Healthy');
            }

            return [
                'Product Name' => $product->name,
                'SKU' => $product->sku,
                'Category' => $product->category ? $product->category->name : 'Uncategorized',
                'Current Stock' => $product->stock,
                "Sold (Last {$this->trackingDays} Days)" => $product->sold_period,
                'Velocity (Units/Day)' => round($velocity, 2),
                'Est. Runway (Days)' => $runway,
                'Status' => $status,
            ];
        });

        // 3. Generate and stream the download directly to the browser
        return (new FastExcel($exportData))->download($filename);
    }

    public function render()
    {
        $products = $this->getBaseQuery()->paginate(15);
        $allStats = $this->getBaseQuery()->get();

        $criticalCount = 0;
        $deadStockCount = 0;

        foreach ($allStats as $stat) {
            $velocity = $stat->sold_period / $this->trackingDays;
            $runway = $velocity > 0 ? $stat->stock / $velocity : 999;

            if ($runway <= 7 && $stat->stock > 0) $criticalCount++;
            if ($velocity < 0.1 && $stat->stock > 20) $deadStockCount++;
        }

        return view('livewire.admin.stock-velocity', [
            'products' => $products,
            'criticalCount' => $criticalCount,
            'deadStockCount' => $deadStockCount,
        ]);
    }
}
