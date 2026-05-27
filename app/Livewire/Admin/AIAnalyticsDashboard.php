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
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;

class AIAnalyticsDashboard extends Component
{
    public $dateRange = 'last_30_days';
    public $isLoadingInsights = false;
    public $aiInsights = [];

    // Cache analytics data to avoid recalculating
    protected $cachedMostAskedProducts = null;
    protected $cachedCustomerComplaints = null;
    protected $cachedChatbotUsage = null;
    protected $cachedCustomerRetention = null;

    protected $queryString = ['dateRange'];

    public function mount()
    {
        $this->loadAIAnalytics();
    }

    public function updatedDateRange()
    {
        // Clear cache when date range changes
        $this->cachedMostAskedProducts = null;
        $this->cachedCustomerComplaints = null;
        $this->cachedChatbotUsage = null;
        $this->cachedCustomerRetention = null;

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
        return match ($this->dateRange) {
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
        if ($this->cachedMostAskedProducts !== null) {
            return $this->cachedMostAskedProducts;
        }

        $mentionedProducts = ChatMessage::where('created_at', '>=', $dateFilter)
            ->where('role', 'user')
            ->get()
            ->map(function ($message) {
                return $this->extractProductMentions($message->content);
            })
            ->filter()
            ->flatMap(function ($products) {
                return $products;
            })
            ->countBy()
            ->sortDesc()
            ->take(10);

        $topSellingProducts = OrderItem::whereHas('order', function ($query) use ($dateFilter) {
            $query->where('created_at', '>=', $dateFilter)
                ->where('status', '!=', 'cancelled');
        })
            ->with('product')
            ->get()
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity_sold' => $items->sum('quantity'),
                    'revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                ];
            })
            ->sortByDesc('quantity_sold')
            ->take(10);

        $result = [
            'mentioned' => $mentionedProducts,
            'top_selling' => $topSellingProducts,
        ];

        $this->cachedMostAskedProducts = $result;

        return $result;
    }

    private function getCustomerComplaints($dateFilter)
    {
        if ($this->cachedCustomerComplaints !== null) {
            return $this->cachedCustomerComplaints;
        }

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
            'problem'
        ];

        $complaints = ChatMessage::where('created_at', '>=', $dateFilter)
            ->where('role', 'user')
            ->get()
            ->filter(function ($message) use ($complaintKeywords) {
                $content = strtolower($message->content);
                foreach ($complaintKeywords as $keyword) {
                    if (str_contains($content, $keyword)) {
                        return true;
                    }
                }
                return false;
            });

        $categories = [
            'Product Quality' => ['broken', 'defective', 'damaged', 'poor quality', 'not working'],
            'Shipping Issues' => ['shipping', 'delivery', 'arrived', 'late', 'damaged in shipping'],
            'Customer Service' => ['support', 'help', 'rude', 'unhelpful', 'waiting'],
            'Returns/Refunds' => ['refund', 'return', 'exchange', 'money back'],
            'Price Issues' => ['expensive', 'overpriced', 'price', 'cost too much'],
        ];

        $categorizedComplaints = [];
        foreach ($categories as $category => $keywords) {
            $count = $complaints->filter(function ($message) use ($keywords) {
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

        $result = [
            'total_complaints' => $complaints->count(),
            'complaint_rate' => ChatMessage::where('created_at', '>=', $dateFilter)
                ->where('role', 'user')
                ->count() > 0
                ? round(($complaints->count() / ChatMessage::where('created_at', '>=', $dateFilter)->where('role', 'user')->count()) * 100, 1)
                : 0,
            'by_category' => $categorizedComplaints,
            'recent_complaints' => $complaints->take(5)->map(function ($message) {
                return [
                    'message' => $message->content,
                    'date' => $message->created_at,
                ];
            }),
        ];

        $this->cachedCustomerComplaints = $result;

        return $result;
    }

    private function getChatbotUsage($dateFilter)
    {
        if ($this->cachedChatbotUsage !== null) {
            return $this->cachedChatbotUsage;
        }

        $chatMessages = ChatMessage::where('created_at', '>=', $dateFilter)->get();

        $userMessages = $chatMessages->where('role', 'user')->count();
        $assistantMessages = $chatMessages->where('role', 'assistant')->count();
        $uniqueSessions = $chatMessages->unique('session_id')->count();

        $dailyUsage = $chatMessages->groupBy(function ($msg) {
            return $msg->created_at->format('Y-m-d');
        })->map(function ($messages, $date) {
            return [
                'date' => $date,
                'messages' => $messages->count(),
                'users' => $messages->unique('session_id')->count(),
            ];
        })->sortKeys()->take(30);

        $sessionLengths = $chatMessages->groupBy('session_id')
            ->map(function ($messages) {
                return $messages->count();
            });

        $result = [
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

        $this->cachedChatbotUsage = $result;

        return $result;
    }

    private function getCustomerRetention($dateFilter)
    {
        if ($this->cachedCustomerRetention !== null) {
            return $this->cachedCustomerRetention;
        }

        $newCustomers = User::where('created_at', '>=', $dateFilter)->count();
        $totalCustomers = User::count();

        $orders = Order::where('created_at', '>=', $dateFilter)
            ->where('status', '!=', 'cancelled')
            ->get();

        $firstTimeBuyers = $orders->groupBy('user_id')
            ->filter(function ($userOrders) {
                return $userOrders->count() == 1;
            })->count();

        $repeatCustomers = $orders->groupBy('user_id')
            ->filter(function ($userOrders) {
                return $userOrders->count() > 1;
            })->count();

        $retentionByCohort = $this->calculateCohortRetention();

        $customerLTV = Order::where('status', '!=', 'cancelled')
            ->groupBy('user_id')
            ->select('user_id', DB::raw('SUM(total_price) as total_spent'))
            ->get()
            ->avg('total_spent') ?? 0;

        $result = [
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

        $this->cachedCustomerRetention = $result;

        return $result;
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
        $hourlyDistribution = $chatMessages->groupBy(function ($msg) {
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

        $firstPurchases = Order::where('status', '!=', 'cancelled')
            ->select('user_id', DB::raw('MIN(created_at) as first_purchase_date'))
            ->groupBy('user_id')
            ->get();

        $cohortsByMonth = $firstPurchases->groupBy(function ($purchase) {
            // first_purchase_date comes from a DB MIN(...) raw select and may be a string,
            // ensure it's parsed to a Carbon instance before formatting.
            return Carbon::parse($purchase->first_purchase_date)->format('Y-m');
        });

        foreach ($cohortsByMonth as $cohort => $customers) {
            $customerIds = $customers->pluck('user_id');

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

        return array_slice($cohorts, -6, 6, true);
    }

    private function extractProductMentions($message)
    {
        $products = Product::all();
        $mentionedProducts = [];
        $messageLower = strtolower($message);

        foreach ($products as $product) {
            if (
                str_contains($messageLower, strtolower($product->name)) ||
                str_contains($messageLower, strtolower($product->sku))
            ) {
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

    /**
     * FIXED: Export actual dashboard data instead of formatted analysis sheets
     */
    public function exportToExcel()
    {
        $dateFilter = $this->getDateFilter();

        // Get all analytics data using cached methods
        $mostAskedProducts = $this->getMostAskedProducts($dateFilter);
        $customerComplaints = $this->getCustomerComplaints($dateFilter);
        $chatbotUsage = $this->getChatbotUsage($dateFilter);
        $customerRetention = $this->getCustomerRetention($dateFilter);

        // Create sheets with ACTUAL DATA from the dashboard
        $sheets = new SheetCollection([
            '1. AI Insights' => $this->formatAIInsightsSheet(),
            '2. Most Asked Products' => $this->formatMostAskedProductsSheet($mostAskedProducts),
            '3. Customer Complaints' => $this->formatCustomerComplaintsSheet($customerComplaints),
            '4. Chatbot Usage' => $this->formatChatbotUsageSheet($chatbotUsage),
            '5. Customer Retention' => $this->formatCustomerRetentionSheet($customerRetention),
        ]);

        $filename = 'ai-analytics-dashboard-' . now()->format('Y-m-d-His') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        return (new FastExcel($sheets))->download($filename);
    }

    /**
     * FIXED: Sheet 1 - AI Insights (keeping this as is, it's good)
     */
    private function formatAIInsightsSheet()
    {
        $data = collect();

        $content = $this->aiInsights['content'] ?? '';
        $sections = preg_split('/\n\s*\n/', trim($content));

        $first = count($sections) > 0 ? array_shift($sections) : null;
        $last = count($sections) > 0 ? array_pop($sections) : null;

        $data->push(['AI Analytics Dashboard Insights', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        if ($first) {
            $data->push(['OVERVIEW', '']);
            $data->push([trim(str_replace('###', '', $first)), '']);
            $data->push(['', '']);
        }

        $data->push(['KEY INSIGHTS', '']);
        foreach ($sections as $section) {
            $lines = explode("\n", trim($section));
            $title = array_shift($lines);
            $data->push([$title, '']);

            foreach ($lines as $line) {
                $data->push(['  • ' . ltrim($line, '- '), '']);
            }
            $data->push(['', '']);
        }

        if ($last) {
            $data->push(['RECOMMENDATIONS', '']);
            $data->push([$last, '']);
        }

        return $data;
    }

    /**
     * FIXED: Sheet 2 - Most Asked Products (actual data from the card)
     */
    private function formatMostAskedProductsSheet($mostAskedProducts)
    {
        $data = collect();

        // Header
        $data->push(['MOST ASKED PRODUCTS (Chatbot Mentions)', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        // Most Asked Products Table (matches the card display)
        $data->push(['Most Asked Products', 'Mention Count', 'Percentage of Top']);
        if ($mostAskedProducts['mentioned']->isNotEmpty()) {
            $maxMentions = $mostAskedProducts['mentioned']->first();
            foreach ($mostAskedProducts['mentioned'] as $productName => $mentionCount) {
                $percentage = round(($mentionCount / $maxMentions) * 100, 1);
                $data->push([$productName, $mentionCount, $percentage . '%']);
            }
        } else {
            $data->push(['No product mentions found', 0, '0%']);
        }

        $data->push(['', '']);

        // Top Selling Products Table (matches the card display)
        $data->push(['Top Selling Products (Same Period)', 'SKU', 'Quantity Sold', 'Revenue']);
        if ($mostAskedProducts['top_selling']->isNotEmpty()) {
            foreach ($mostAskedProducts['top_selling'] as $product) {
                $data->push([
                    $product['name'],
                    $product['sku'],
                    $product['quantity_sold'],
                    '$' . number_format($product['revenue'], 2)
                ]);
            }
        } else {
            $data->push(['No selling data available', '', 0, '$0']);
        }

        return $data;
    }

    /**
     * FIXED: Sheet 3 - Customer Complaints (actual data from the card)
     */
    private function formatCustomerComplaintsSheet($customerComplaints)
    {
        $data = collect();

        // Header
        $data->push(['CUSTOMER COMPLAINTS', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        // Summary (matches card header)
        $data->push(['Summary', '']);
        $data->push(['Total Complaints', $customerComplaints['total_complaints']]);
        $data->push(['Complaint Rate', $customerComplaints['complaint_rate'] . '% of conversations']);
        $data->push(['', '']);

        // Complaints by Category (matches the bar chart in the card)
        $data->push(['Complaints by Category', 'Count', 'Percentage of Total']);
        if ($customerComplaints['total_complaints'] > 0) {
            foreach ($customerComplaints['by_category'] as $category => $count) {
                $percentage = round(($count / max($customerComplaints['by_category'])) * 100, 1);
                $data->push([$category, $count, $percentage . '%']);
            }
        } else {
            foreach (array_keys($customerComplaints['by_category']) as $category) {
                $data->push([$category, 0, '0%']);
            }
        }

        $data->push(['', '']);

        // Recent Complaints (matches the card's recent complaints list)
        $data->push(['Recent Complaints', 'Date', '']);
        if ($customerComplaints['recent_complaints']->isNotEmpty()) {
            foreach ($customerComplaints['recent_complaints'] as $complaint) {
                $data->push([
                    $complaint['message'],
                    $complaint['date']->format('Y-m-d H:i:s'),
                    ''
                ]);
            }
        } else {
            $data->push(['No recent complaints', '', '']);
        }

        return $data;
    }

    /**
     * FIXED: Sheet 4 - Chatbot Usage (actual data from the card)
     */
    private function formatChatbotUsageSheet($chatbotUsage)
    {
        $data = collect();

        // Header
        $data->push(['CHATBOT USAGE', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        // Key Metrics (matches the card's metrics display)
        $data->push(['Key Metrics', '']);
        $data->push(['Total Conversations', $chatbotUsage['unique_sessions']]);
        $data->push(['Avg Messages per Session', $chatbotUsage['avg_messages_per_session']]);
        $data->push(['Total Messages', $chatbotUsage['total_messages']]);
        $data->push(['User Messages', $chatbotUsage['user_messages']]);
        $data->push(['Assistant Messages', $chatbotUsage['assistant_messages']]);
        $data->push(['Avg Session Duration', $chatbotUsage['avg_session_duration'] . ' minutes']);
        $data->push(['', '']);

        // Session Length Distribution (matches the card's distribution)
        $data->push(['Session Length Distribution', 'Number of Sessions', 'Percentage']);
        $totalSessions = $chatbotUsage['unique_sessions'];
        foreach ($chatbotUsage['session_length_distribution'] as $range => $count) {
            $percentage = $totalSessions > 0 ? round(($count / $totalSessions) * 100, 1) : 0;
            $data->push([$range, $count, $percentage . '%']);
        }

        $data->push(['', '']);

        // Peak Hours (matches the card's peak hours display)
        $data->push(['Peak Usage Time', '']);
        $data->push(['Peak Hour', $chatbotUsage['peak_hours']['peak_hour']]);
        $data->push(['Messages at Peak', $chatbotUsage['peak_hours']['peak_hour_count']]);

        return $data;
    }

    /**
     * FIXED: Sheet 5 - Customer Retention (actual data from the card)
     */
    private function formatCustomerRetentionSheet($customerRetention)
    {
        $data = collect();

        // Header
        $data->push(['CUSTOMER RETENTION', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        // Key Metrics (matches the card's metrics)
        $data->push(['Key Metrics', '']);
        $data->push(['Retention Rate', $customerRetention['retention_rate'] . '%']);
        $data->push(['Repeat Purchase Rate', $customerRetention['repeat_purchase_rate'] . '%']);
        $data->push(['Average Customer LTV', '$' . number_format($customerRetention['customer_ltv'], 2)]);
        $data->push(['', '']);

        // Customer Segments (matches the card's segments)
        $data->push(['Customer Segments', 'Count']);
        $data->push(['New Customers', $customerRetention['new_customers']]);
        $data->push(['First-Time Buyers', $customerRetention['first_time_buyers']]);
        $data->push(['Repeat Customers', $customerRetention['repeat_customers']]);
        $data->push(['Total Customers', $customerRetention['total_customers']]);
        $data->push(['', '']);

        // Retention by Cohort (matches the card's cohort display)
        if (!empty($customerRetention['retention_by_cohort'])) {
            $data->push(['Retention by Cohort', 'Month 1 Retention', 'Month 2 Retention', 'Month 3 Retention']);
            foreach (array_slice($customerRetention['retention_by_cohort'], -3, 3, true) as $cohort => $retention) {
                $data->push([
                    $cohort,
                    ($retention['month_1'] ?? 0) . '%',
                    ($retention['month_2'] ?? 0) . '%',
                    ($retention['month_3'] ?? 0) . '%'
                ]);
            }
        }

        return $data;
    }

    // Helper methods for formatting (keep existing ones)
    private function getSeverityLevel($category, $percentage)
    {
        if ($percentage > 30)
            return 'Critical';
        if ($percentage > 20)
            return 'High';
        if ($percentage > 10)
            return 'Medium';
        return 'Low';
    }

    private function detectComplaintCategory($message)
    {
        $message = strtolower($message);

        if (str_contains($message, 'quality') || str_contains($message, 'broken') || str_contains($message, 'defective')) {
            return 'Product Quality';
        }
        if (str_contains($message, 'shipping') || str_contains($message, 'delivery')) {
            return 'Shipping Issues';
        }
        if (str_contains($message, 'refund') || str_contains($message, 'return')) {
            return 'Returns/Refunds';
        }
        if (str_contains($message, 'price') || str_contains($message, 'expensive')) {
            return 'Price Issues';
        }
        if (str_contains($message, 'support') || str_contains($message, 'service')) {
            return 'Customer Service';
        }

        return 'Other';
    }

    private function getEngagementLevel($range)
    {
        return match ($range) {
            '1-5 messages' => 'Low',
            '6-10 messages' => 'Medium',
            '11-20 messages' => 'High',
            '20+ messages' => 'Very High',
            default => 'N/A'
        };
    }

    private function getUsagePattern($hour, $peakHour)
    {
        if ($hour == $peakHour)
            return 'Peak';
        $hourInt = (int) $hour;
        if ($hourInt >= 9 && $hourInt <= 17)
            return 'Business Hours';
        if ($hourInt >= 18 && $hourInt <= 22)
            return 'Evening';
        return 'Off-Hours';
    }

    private function getRetentionTrend($retention)
    {
        $month1 = $retention['month_1'] ?? 0;
        $month2 = $retention['month_2'] ?? 0;
        $month3 = $retention['month_3'] ?? 0;

        if ($month1 > $month2 && $month2 > $month3)
            return 'Declining';
        if ($month1 < $month2 && $month2 < $month3)
            return 'Improving';
        if ($month1 == $month2 && $month2 == $month3)
            return 'Stable';
        return 'Fluctuating';
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
