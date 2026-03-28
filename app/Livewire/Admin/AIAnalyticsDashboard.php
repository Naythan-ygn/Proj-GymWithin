<?php

namespace App\Livewire\Admin;

use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AIAnalyticsDashboard extends Component
{
    public $dateRange = 'last_30_days';
    public $isLoadingInsights = false;
    public $aiInsights = [];
    
    protected $queryString = ['dateRange'];
    
    public function mount()
    {
        $this->loadAIAnalytics();
    }
    
    public function updatedDateRange()
    {
        $this->loadAIAnalytics();
    }
    
    public function loadAIAnalytics()
    {
        $this->isLoadingInsights = true;
        
        $dateFilter = $this->getDateFilter();
        
        $analyticsData = [
            'most_asked_products' => $this->getMostAskedProducts($dateFilter),
            'customer_complaints' => $this->getCustomerComplaints($dateFilter),
            'chatbot_usage' => $this->getChatbotUsage($dateFilter),
            'customer_retention' => $this->getCustomerRetention($dateFilter),
        ];
        
        $this->aiInsights = $this->generateAIInsights($analyticsData);
        
        $this->isLoadingInsights = false;
    }
    
    private function getDateFilter()
    {
        return match($this->dateRange) {
            'today' => Carbon::today(),
            'last_7_days' => Carbon::now()->subDays(7),
            'last_30_days' => Carbon::now()->subDays(30),
            'last_90_days' => Carbon::now()->subDays(90),
            'this_year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->subDays(30),
        };
    }
    
    private function getMostAskedProducts($dateFilter)
    {
        // Get products mentioned in chatbot conversations
        $mentionedProducts = ChatMessage::where('created_at', '>=', $dateFilter)
            ->where('role', 'user')
            ->get()
            ->map(function($message) {
                return $this->extractProductMentions($message->content);
            })
            ->filter()
            ->flatMap(function($products) {
                return $products;
            })
            ->countBy()
            ->sortDesc()
            ->take(10);
        
        // Get actual sales data for top products
        $topSellingProducts = OrderItem::whereHas('order', function($query) use ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter)
                      ->where('status', '!=', 'cancelled');
            })
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function($items) {
                $product = $items->first()->product;
                return [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity_sold' => $items->sum('quantity'),
                    'revenue' => $items->sum(function($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('quantity_sold')
            ->take(10);
        
        return [
            'mentioned' => $mentionedProducts,
            'top_selling' => $topSellingProducts,
        ];
    }
    
    private function getCustomerComplaints($dateFilter)
    {
        $complaintKeywords = [
            'broken', 'defective', 'damaged', 'not working', 'doesn\'t work',
            'poor quality', 'disappointed', 'terrible', 'awful', 'worst',
            'complaint', 'refund', 'return', 'exchange', 'frustrated',
            'angry', 'unhappy', 'dissatisfied', 'issue', 'problem'
        ];
        
        $complaints = ChatMessage::where('created_at', '>=', $dateFilter)
            ->where('role', 'user')
            ->get()
            ->filter(function($message) use ($complaintKeywords) {
                $content = strtolower($message->content);
                foreach ($complaintKeywords as $keyword) {
                    if (str_contains($content, $keyword)) {
                        return true;
                    }
                }
                return false;
            });
        
        // Categorize complaints
        $categories = [
            'Product Quality' => ['broken', 'defective', 'damaged', 'poor quality', 'not working'],
            'Shipping Issues' => ['shipping', 'delivery', 'arrived', 'late', 'damaged in shipping'],
            'Customer Service' => ['support', 'help', 'rude', 'unhelpful', 'waiting'],
            'Returns/Refunds' => ['refund', 'return', 'exchange', 'money back'],
            'Price Issues' => ['expensive', 'overpriced', 'price', 'cost too much'],
        ];
        
        $categorizedComplaints = [];
        foreach ($categories as $category => $keywords) {
            $count = $complaints->filter(function($message) use ($keywords) {
                $content = strtolower($message->content);
                foreach ($keywords as $keyword) {
                    if (str_contains($content, $keyword)) {
                        return true;
                    }
                }
                return false;
            })->count();
            
            $categorizedComplaints[$category] = $count;
        }
        
        return [
            'total_complaints' => $complaints->count(),
            'complaint_rate' => ChatMessage::where('created_at', '>=', $dateFilter)
                ->where('role', 'user')
                ->count() > 0 
                ? round(($complaints->count() / ChatMessage::where('created_at', '>=', $dateFilter)->where('role', 'user')->count()) * 100, 1)
                : 0,
            'by_category' => $categorizedComplaints,
            'recent_complaints' => $complaints->take(5)->map(function($message) {
                return [
                    'message' => $message->content,
                    'date' => $message->created_at,
                ];
            }),
        ];
    }
    
    private function getChatbotUsage($dateFilter)
    {
        $chatMessages = ChatMessage::where('created_at', '>=', $dateFilter)->get();
        
        $userMessages = $chatMessages->where('role', 'user')->count();
        $assistantMessages = $chatMessages->where('role', 'assistant')->count();
        $uniqueSessions = $chatMessages->unique('session_id')->count();
        
        // Get daily usage trend
        $dailyUsage = $chatMessages->groupBy(function($msg) {
            return $msg->created_at->format('Y-m-d');
        })->map(function($messages, $date) {
            return [
                'date' => $date,
                'messages' => $messages->count(),
                'users' => $messages->unique('session_id')->count(),
            ];
        })->sortKeys()->take(30);
        
        // Get user engagement metrics
        $sessionLengths = $chatMessages->groupBy('session_id')
            ->map(function($messages) {
                return $messages->count();
            });
        
        return [
            'total_messages' => $chatMessages->count(),
            'user_messages' => $userMessages,
            'assistant_messages' => $assistantMessages,
            'unique_sessions' => $uniqueSessions,
            'avg_messages_per_session' => $uniqueSessions > 0 ? round($chatMessages->count() / $uniqueSessions, 1) : 0,
            'avg_session_duration' => $this->calculateAvgSessionDuration($chatMessages),
            'daily_usage' => $dailyUsage,
            'session_length_distribution' => [
                '1-5 messages' => $sessionLengths->filter(fn($len) => $len <= 5)->count(),
                '6-10 messages' => $sessionLengths->filter(fn($len) => $len > 5 && $len <= 10)->count(),
                '11-20 messages' => $sessionLengths->filter(fn($len) => $len > 10 && $len <= 20)->count(),
                '20+ messages' => $sessionLengths->filter(fn($len) => $len > 20)->count(),
            ],
            'peak_hours' => $this->getPeakHours($chatMessages),
        ];
    }
    
    private function getCustomerRetention($dateFilter)
    {
        // Get new vs returning customers
        $newCustomers = User::where('created_at', '>=', $dateFilter)->count();
        $totalCustomers = User::count();
        
        $orders = Order::where('created_at', '>=', $dateFilter)
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $firstTimeBuyers = $orders->groupBy('user_id')
            ->filter(function($userOrders) {
                return $userOrders->count() == 1;
            })->count();
        
        $repeatCustomers = $orders->groupBy('user_id')
            ->filter(function($userOrders) {
                return $userOrders->count() > 1;
            })->count();
        
        // Calculate retention rate over time
        $retentionByCohort = $this->calculateCohortRetention();
        
        // Customer lifetime value
        $customerLTV = Order::where('status', '!=', 'cancelled')
            ->groupBy('user_id')
            ->select('user_id', DB::raw('SUM(total_price) as total_spent'))
            ->get()
            ->avg('total_spent') ?? 0;
        
        return [
            'new_customers' => $newCustomers,
            'total_customers' => $totalCustomers,
            'first_time_buyers' => $firstTimeBuyers,
            'repeat_customers' => $repeatCustomers,
            'repeat_purchase_rate' => $orders->groupBy('user_id')->count() > 0 
                ? round(($repeatCustomers / $orders->groupBy('user_id')->count()) * 100, 1)
                : 0,
            'retention_rate' => $totalCustomers > 0 
                ? round(($repeatCustomers / $totalCustomers) * 100, 1)
                : 0,
            'retention_by_cohort' => $retentionByCohort,
            'customer_ltv' => round($customerLTV, 2),
        ];
    }
    
    private function calculateAvgSessionDuration($chatMessages)
    {
        $sessions = $chatMessages->groupBy('session_id');
        $totalDuration = 0;
        $sessionCount = 0;
        
        foreach ($sessions as $sessionId => $messages) {
            if ($messages->count() >= 2) {
                $firstMessage = $messages->first()->created_at;
                $lastMessage = $messages->last()->created_at;
                $duration = $firstMessage->diffInMinutes($lastMessage);
                $totalDuration += $duration;
                $sessionCount++;
            }
        }
        
        return $sessionCount > 0 ? round($totalDuration / $sessionCount, 1) : 0;
    }
    
    private function getPeakHours($chatMessages)
    {
        $hourlyDistribution = $chatMessages->groupBy(function($msg) {
            return $msg->created_at->format('H');
        })->map->count();
        
        $peakHour = $hourlyDistribution->sortDesc()->keys()->first();
        
        return [
            'distribution' => $hourlyDistribution,
            'peak_hour' => $peakHour ? date('g A', strtotime($peakHour . ':00')) : 'N/A',
            'peak_hour_count' => $hourlyDistribution[$peakHour] ?? 0,
        ];
    }
    
    private function calculateCohortRetention()
    {
        $cohorts = [];
        
        // Get customers by month of first purchase
        $firstPurchases = Order::where('status', '!=', 'cancelled')
            ->select('user_id', DB::raw('MIN(created_at) as first_purchase_date'))
            ->groupBy('user_id')
            ->get();
        
        $cohortsByMonth = $firstPurchases->groupBy(function($purchase) {
            return $purchase->first_purchase_date->format('Y-m');
        });
        
        foreach ($cohortsByMonth as $cohort => $customers) {
            $customerIds = $customers->pluck('user_id');
            
            // Calculate retention for months 1, 2, 3
            $retention = [];
            for ($i = 1; $i <= 3; $i++) {
                $monthAgo = Carbon::parse($cohort . '-01')->addMonths($i);
                $returningCustomers = Order::whereIn('user_id', $customerIds)
                    ->whereYear('created_at', $monthAgo->year)
                    ->whereMonth('created_at', $monthAgo->month)
                    ->distinct('user_id')
                    ->count('user_id');
                
                $retention["month_{$i}"] = $customerIds->count() > 0 
                    ? round(($returningCustomers / $customerIds->count()) * 100, 1)
                    : 0;
            }
            
            $cohorts[$cohort] = $retention;
        }
        
        return array_slice($cohorts, -6, 6, true); // Last 6 cohorts
    }
    
    private function extractProductMentions($message)
    {
        $products = Product::all();
        $mentionedProducts = [];
        $messageLower = strtolower($message);
        
        foreach ($products as $product) {
            if (str_contains($messageLower, strtolower($product->name)) ||
                str_contains($messageLower, strtolower($product->sku))) {
                $mentionedProducts[] = $product->name;
            }
        }
        
        return $mentionedProducts;
    }
    
    private function generateAIInsights($analyticsData)
    {
        $prompt = "Analyze this fitness equipment e-commerce data and provide 3-5 key actionable insights:
        
        Most Asked About Products:
        - Top mentioned: " . json_encode($analyticsData['most_asked_products']['mentioned']->take(5)->toArray()) . "
        - Top selling: " . json_encode($analyticsData['most_asked_products']['top_selling']->take(5)->toArray()) . "
        
        Customer Complaints:
        - Total: {$analyticsData['customer_complaints']['total_complaints']}
        - Rate: {$analyticsData['customer_complaints']['complaint_rate']}%
        - Categories: " . json_encode($analyticsData['customer_complaints']['by_category']) . "
        
        Chatbot Usage:
        - Conversations: {$analyticsData['chatbot_usage']['unique_sessions']}
        - Avg messages/session: {$analyticsData['chatbot_usage']['avg_messages_per_session']}
        
        Customer Retention:
        - Retention rate: {$analyticsData['customer_retention']['retention_rate']}%
        - Repeat purchase rate: {$analyticsData['customer_retention']['repeat_purchase_rate']}%
        
        Provide concise recommendations focusing on:
        1. Product opportunities based on demand
        2. Service improvements based on complaints
        3. Chatbot optimization suggestions
        4. Customer retention strategies";
        
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.key'),
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a GymWithin business analyst. Provide actionable, concise insights.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);
            
            if ($response->successful()) {
                return [
                    'content' => $response->json('choices.0.message.content'),
                    'has_insights' => true,
                ];
            }
        } catch (\Exception $e) {
            Log::error('AI Analytics Error: ' . $e->getMessage());
        }
        
        return [
            'content' => "Based on recent data:\n\n• Focus marketing on top-asked products\n• Address {$analyticsData['customer_complaints']['by_category']['Product Quality']} quality concerns\n• Chatbot handling {$analyticsData['chatbot_usage']['unique_sessions']} conversations\n• Retention rate at {$analyticsData['customer_retention']['retention_rate']}% - consider loyalty program",
            'has_insights' => false,
        ];
    }
    
    public function exportToExcel()
    {
        $dateFilter = $this->getDateFilter();
        $data = [
            'most_asked_products' => $this->getMostAskedProducts($dateFilter),
            'customer_complaints' => $this->getCustomerComplaints($dateFilter),
            'chatbot_usage' => $this->getChatbotUsage($dateFilter),
            'customer_retention' => $this->getCustomerRetention($dateFilter),
            'exported_at' => Carbon::now(),
        ];
        
        $filename = 'ai_analytics_report_' . Carbon::now()->format('Y-m-d_His') . '.json';
        
        return response()->streamDownload(function() use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }
    
    public function render()
    {
        $dateFilter = $this->getDateFilter();
        
        return view('livewire.admin.ai-analytics-dashboard', [
            'mostAskedProducts' => $this->getMostAskedProducts($dateFilter),
            'customerComplaints' => $this->getCustomerComplaints($dateFilter),
            'chatbotUsage' => $this->getChatbotUsage($dateFilter),
            'customerRetention' => $this->getCustomerRetention($dateFilter),
        ]);
    }
}
