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
        // Return cached data if available
        if ($this->cachedMostAskedProducts !== null) {
            return $this->cachedMostAskedProducts;
        }

        // Get products mentioned in chatbot conversations
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

        // Get actual sales data for top products
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

        // Cache the result
        $this->cachedMostAskedProducts = $result;

        return $result;
    }

    private function getCustomerComplaints($dateFilter)
    {
        // Return cached data if available
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

        // Cache the result
        $this->cachedCustomerComplaints = $result;

        return $result;
    }

    private function getChatbotUsage($dateFilter)
    {
        // Return cached data if available
        if ($this->cachedChatbotUsage !== null) {
            return $this->cachedChatbotUsage;
        }

        $chatMessages = ChatMessage::where('created_at', '>=', $dateFilter)->get();

        $userMessages = $chatMessages->where('role', 'user')->count();
        $assistantMessages = $chatMessages->where('role', 'assistant')->count();
        $uniqueSessions = $chatMessages->unique('session_id')->count();

        // Get daily usage trend
        $dailyUsage = $chatMessages->groupBy(function ($msg) {
            return $msg->created_at->format('Y-m-d');
        })->map(function ($messages, $date) {
            return [
                'date' => $date,
                'messages' => $messages->count(),
                'users' => $messages->unique('session_id')->count(),
            ];
        })->sortKeys()->take(30);

        // Get user engagement metrics
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

        // Cache the result
        $this->cachedChatbotUsage = $result;

        return $result;
    }

    private function getCustomerRetention($dateFilter)
    {
        // Return cached data if available
        if ($this->cachedCustomerRetention !== null) {
            return $this->cachedCustomerRetention;
        }

        // Get new vs returning customers
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

        // Calculate retention rate over time
        $retentionByCohort = $this->calculateCohortRetention();

        // Customer lifetime value
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

        // Cache the result
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

        // Get customers by month of first purchase
        $firstPurchases = Order::where('status', '!=', 'cancelled')
            ->select('user_id', DB::raw('MIN(created_at) as first_purchase_date'))
            ->groupBy('user_id')
            ->get();

        $cohortsByMonth = $firstPurchases->groupBy(function ($purchase) {
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

    public function exportToExcel()
    {
        $dateFilter = $this->getDateFilter();

        // Get all analytics data using cached methods
        $mostAskedProducts = $this->getMostAskedProducts($dateFilter);
        $customerComplaints = $this->getCustomerComplaints($dateFilter);
        $chatbotUsage = $this->getChatbotUsage($dateFilter);
        $customerRetention = $this->getCustomerRetention($dateFilter);

        // Create comprehensive sheets
        $productDemandSheet = $this->formatProductDemandSheet($mostAskedProducts);
        $complaintsSheet = $this->formatComplaintsSheet($customerComplaints);
        $chatbotSheet = $this->formatChatbotSheet($chatbotUsage);
        $retentionSheet = $this->formatRetentionSheet($customerRetention);
        $aiInsightsSheet = $this->formatAIInsightsSheet();

        $sheets = new SheetCollection([
            '1. AI Insights' => $aiInsightsSheet,
            '2. Product Demand' => $productDemandSheet,
            '3. Customer Complaints' => $complaintsSheet,
            '4. Chatbot Usage' => $chatbotSheet,
            '5. Customer Retention' => $retentionSheet,
        ]);

        $filename = 'ai-analytics-dashboard-' . now()->format('Y-m-d-His') . '.xlsx';

        // Clean output buffers before download
        if (ob_get_length()) {
            ob_end_clean();
        }

        return (new FastExcel($sheets))->download($filename);
    }

    private function formatAIInsightsSheet()
    {
        $data = collect();

        // Parse AI insights content
        $content = $this->aiInsights['content'] ?? '';
        $sections = preg_split('/\n\s*\n/', trim($content));

        $first = count($sections) > 0 ? array_shift($sections) : null;
        $last = count($sections) > 0 ? array_pop($sections) : null;

        // Add header
        $data->push(['AI Analytics Dashboard Insights', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['Date Range:', ucwords(str_replace('_', ' ', $this->dateRange))]);
        $data->push(['', '']);

        // Add subtitle
        if ($first) {
            $data->push(['OVERVIEW', '']);
            $data->push([trim(str_replace('###', '', $first)), '']);
            $data->push(['', '']);
        }

        // Add main insights
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

        // Add footer
        if ($last) {
            $data->push(['RECOMMENDATIONS', '']);
            $data->push([$last, '']);
        }

        return $data;
    }

    private function formatProductDemandSheet($mostAskedProducts)
    {
        $data = collect();

        // Title section
        $data->push(['PRODUCT DEMAND ANALYSIS', '']);
        $data->push(['', '']);

        // Most Asked Products Section
        $data->push(['MOST ASKED PRODUCTS (Chatbot Mentions)', '']);
        $data->push(['Product Name', 'Mention Count', 'Popularity Score']);

        if ($mostAskedProducts['mentioned']->isNotEmpty()) {
            $maxMentions = $mostAskedProducts['mentioned']->first();
            foreach ($mostAskedProducts['mentioned'] as $productName => $mentionCount) {
                $popularityScore = round(($mentionCount / $maxMentions) * 100, 1);
                $data->push([
                    $productName,
                    $mentionCount,
                    $popularityScore . '%'
                ]);
            }
        } else {
            $data->push(['No product mentions found', '0', '0%']);
        }

        $data->push(['', '']);

        // Top Selling Products Section
        $data->push(['TOP SELLING PRODUCTS (Same Period)', '']);
        $data->push(['Product Name', 'SKU', 'Quantity Sold', 'Revenue', 'Average Price']);

        if ($mostAskedProducts['top_selling']->isNotEmpty()) {
            foreach ($mostAskedProducts['top_selling'] as $product) {
                $avgPrice = $product['quantity_sold'] > 0
                    ? round($product['revenue'] / $product['quantity_sold'], 2)
                    : 0;
                $data->push([
                    $product['name'],
                    $product['sku'],
                    $product['quantity_sold'],
                    '$' . number_format($product['revenue'], 2),
                    '$' . number_format($avgPrice, 2)
                ]);
            }
        } else {
            $data->push(['No selling data available', '', '0', '$0', '$0']);
        }

        $data->push(['', '']);

        // Gap Analysis (Products asked but not selling well)
        $data->push(['DEMAND VS SALES GAP ANALYSIS', '']);
        $data->push(['Product Name', 'Mentions', 'Sales Rank', 'Opportunity Score']);

        $mentionedProducts = $mostAskedProducts['mentioned']->keys()->toArray();
        $sellingProducts = $mostAskedProducts['top_selling']->pluck('name')->toArray();
        $gapProducts = array_diff($mentionedProducts, $sellingProducts);

        if (!empty($gapProducts)) {
            foreach (array_slice($gapProducts, 0, 10) as $product) {
                $mentions = $mostAskedProducts['mentioned'][$product];
                $data->push([
                    $product,
                    $mentions,
                    'Not in top 10 selling',
                    'High - Investigate why not selling'
                ]);
            }
        } else {
            $data->push(['No significant gaps found', '', '', '']);
        }

        return $data;
    }

    private function formatComplaintsSheet($customerComplaints)
    {
        $data = collect();

        // Title section
        $data->push(['CUSTOMER COMPLAINTS ANALYSIS', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['', '']);

        // Summary Section
        $data->push(['SUMMARY STATISTICS', '']);
        $data->push(['Total Complaints', $customerComplaints['total_complaints']]);
        $data->push(['Complaint Rate', $customerComplaints['complaint_rate'] . '% of conversations']);
        $data->push(['', '']);

        // Complaint Categories Distribution
        $data->push(['COMPLAINTS BY CATEGORY', '']);
        $data->push(['Category', 'Number of Complaints', 'Percentage', 'Severity Level']);

        if ($customerComplaints['total_complaints'] > 0) {
            foreach ($customerComplaints['by_category'] as $category => $count) {
                $percentage = round(($count / $customerComplaints['total_complaints']) * 100, 1);
                $severity = $this->getSeverityLevel($category, $percentage);
                $data->push([
                    $category,
                    $count,
                    $percentage . '%',
                    $severity
                ]);
            }
        } else {
            $data->push(['No complaints recorded', '0', '0%', 'N/A']);
        }

        $data->push(['', '']);
        $data->push(['TOTAL', $customerComplaints['total_complaints'], '100%', '']);
        $data->push(['', '']);

        // Recent Complaints
        $data->push(['RECENT COMPLAINTS (Last 5)', '']);
        $data->push(['Date/Time', 'Complaint Message', 'Category (Auto-detected)']);

        if ($customerComplaints['recent_complaints']->isNotEmpty()) {
            foreach ($customerComplaints['recent_complaints'] as $complaint) {
                $category = $this->detectComplaintCategory($complaint['message']);
                $data->push([
                    $complaint['date']->format('Y-m-d H:i:s'),
                    $complaint['message'],
                    $category
                ]);
            }
        } else {
            $data->push(['No recent complaints', '', '']);
        }

        $data->push(['', '']);

        // Action Items
        $data->push(['RECOMMENDED ACTIONS', '']);
        $data->push(['Priority', 'Action Item', 'Expected Impact']);

        $topCategory = collect($customerComplaints['by_category'])->sortDesc()->keys()->first();
        if ($topCategory && $customerComplaints['total_complaints'] > 0) {
            $data->push(['HIGH', "Address {$topCategory} complaints - most common issue", 'Reduce complaints by 20-30%']);
            $data->push(['MEDIUM', 'Review customer service response templates', 'Improve resolution time']);
            $data->push(['MEDIUM', 'Analyze refund/return process efficiency', 'Increase customer satisfaction']);
        }

        return $data;
    }

    private function formatChatbotSheet($chatbotUsage)
    {
        $data = collect();

        // Title section
        $data->push(['CHATBOT USAGE ANALYTICS', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['', '']);

        // Key Metrics
        $data->push(['KEY PERFORMANCE METRICS', '']);
        $data->push(['Metric', 'Value', 'Benchmark', 'Status']);

        $metrics = [
            ['Total Conversations', $chatbotUsage['unique_sessions'], 'N/A', ''],
            ['Total Messages', $chatbotUsage['total_messages'], 'N/A', ''],
            ['User Messages', $chatbotUsage['user_messages'], 'N/A', ''],
            ['Assistant Messages', $chatbotUsage['assistant_messages'], 'N/A', ''],
            ['Avg Messages/Session', $chatbotUsage['avg_messages_per_session'], '5-10', $chatbotUsage['avg_messages_per_session'] >= 5 ? 'Good' : 'Needs Improvement'],
            ['Avg Session Duration', $chatbotUsage['avg_session_duration'] . ' min', '2-5 min', $chatbotUsage['avg_session_duration'] >= 2 ? 'Good' : 'Low Engagement'],
        ];

        foreach ($metrics as $metric) {
            $data->push($metric);
        }

        $data->push(['', '']);

        // Session Length Distribution
        $data->push(['SESSION LENGTH DISTRIBUTION', '']);
        $data->push(['Session Length Range', 'Number of Sessions', 'Percentage', 'Engagement Level']);

        $totalSessions = $chatbotUsage['unique_sessions'];
        foreach ($chatbotUsage['session_length_distribution'] as $range => $count) {
            $percentage = $totalSessions > 0 ? round(($count / $totalSessions) * 100, 1) : 0;
            $engagement = $this->getEngagementLevel($range);
            $data->push([$range, $count, $percentage . '%', $engagement]);
        }

        $data->push(['', '']);

        // Peak Hours Analysis
        $data->push(['PEAK USAGE ANALYSIS', '']);
        $data->push(['Peak Hour', $chatbotUsage['peak_hours']['peak_hour']]);
        $data->push(['Messages During Peak', $chatbotUsage['peak_hours']['peak_hour_count']]);
        $data->push(['', '']);

        // Hourly Distribution
        $data->push(['HOURLY DISTRIBUTION', '']);
        $data->push(['Hour', 'Message Count', 'Usage Pattern']);

        if (!empty($chatbotUsage['peak_hours']['distribution'])) {
            foreach ($chatbotUsage['peak_hours']['distribution'] as $hour => $count) {
                $hourFormatted = date('g A', strtotime($hour . ':00'));
                $pattern = $this->getUsagePattern($hour, $chatbotUsage['peak_hours']['peak_hour']);
                $data->push([$hourFormatted, $count, $pattern]);
            }
        }

        $data->push(['', '']);

        // Optimization Suggestions
        $data->push(['OPTIMIZATION SUGGESTIONS', '']);
        $data->push(['Area', 'Suggestion', 'Priority']);

        if ($chatbotUsage['avg_messages_per_session'] < 5) {
            $data->push(['Engagement', 'Add proactive suggestions to increase conversation length', 'HIGH']);
        }
        if ($chatbotUsage['avg_session_duration'] < 2) {
            $data->push(['Retention', 'Improve response quality to keep users engaged', 'MEDIUM']);
        }
        $data->push(['Staffing', "Increase support during {$chatbotUsage['peak_hours']['peak_hour']}", 'MEDIUM']);
        $data->push(['Features', 'Consider adding quick reply buttons for common queries', 'LOW']);

        return $data;
    }

    private function formatRetentionSheet($customerRetention)
    {
        $data = collect();

        // Title section
        $data->push(['CUSTOMER RETENTION ANALYSIS', '']);
        $data->push(['Generated on:', now()->format('F j, Y g:i A')]);
        $data->push(['', '']);

        // Key Metrics
        $data->push(['KEY RETENTION METRICS', '']);
        $data->push(['Metric', 'Value', 'Industry Benchmark', 'Performance']);

        $performance = $customerRetention['retention_rate'] >= 30 ? 'Excellent' : ($customerRetention['retention_rate'] >= 20 ? 'Average' : 'Needs Improvement');
        $repeatPerformance = $customerRetention['repeat_purchase_rate'] >= 40 ? 'Excellent' : ($customerRetention['repeat_purchase_rate'] >= 25 ? 'Average' : 'Needs Improvement');

        $data->push(['Retention Rate', $customerRetention['retention_rate'] . '%', '20-30%', $performance]);
        $data->push(['Repeat Purchase Rate', $customerRetention['repeat_purchase_rate'] . '%', '25-40%', $repeatPerformance]);
        $data->push(['Average Customer LTV', '$' . number_format($customerRetention['customer_ltv'], 2), 'N/A', '']);
        $data->push(['', '']);

        // Customer Segments
        $data->push(['CUSTOMER SEGMENTATION', '']);
        $data->push(['Segment', 'Count', 'Percentage', 'Action Priority']);

        $totalCustomers = $customerRetention['total_customers'];
        $newCustomersPct = $totalCustomers > 0 ? round(($customerRetention['new_customers'] / $totalCustomers) * 100, 1) : 0;
        $repeatPct = $totalCustomers > 0 ? round(($customerRetention['repeat_customers'] / $totalCustomers) * 100, 1) : 0;
        $firstTimePct = $customerRetention['first_time_buyers'] > 0 ? round(($customerRetention['first_time_buyers'] / ($customerRetention['first_time_buyers'] + $customerRetention['repeat_customers'])) * 100, 1) : 0;

        $data->push(['New Customers (Registered)', $customerRetention['new_customers'], $newCustomersPct . '%', 'Nurture']);
        $data->push(['First-Time Buyers', $customerRetention['first_time_buyers'], $firstTimePct . '% of buyers', 'Convert to repeat']);
        $data->push(['Repeat Customers', $customerRetention['repeat_customers'], $repeatPct . '%', 'Reward loyalty']);
        $data->push(['Total Customers', $customerRetention['total_customers'], '100%', '']);

        $data->push(['', '']);

        // Retention by Cohort
        if (!empty($customerRetention['retention_by_cohort'])) {
            $data->push(['RETENTION BY COHORT (Last 6 Months)', '']);
            $data->push(['Cohort (Month)', 'Month 1 Retention', 'Month 2 Retention', 'Month 3 Retention', 'Trend']);

            foreach ($customerRetention['retention_by_cohort'] as $cohort => $retention) {
                $trend = $this->getRetentionTrend($retention);
                $data->push([
                    $cohort,
                    ($retention['month_1'] ?? 0) . '%',
                    ($retention['month_2'] ?? 0) . '%',
                    ($retention['month_3'] ?? 0) . '%',
                    $trend
                ]);
            }
        }

        $data->push(['', '']);

        // Actionable Recommendations
        $data->push(['RETENTION STRATEGY RECOMMENDATIONS', '']);
        $data->push(['Strategy', 'Target Segment', 'Expected ROI', 'Implementation Effort']);

        $recommendations = [];

        if ($customerRetention['retention_rate'] < 25) {
            $recommendations[] = ['Loyalty Program', 'All Customers', 'High', 'Medium'];
            $recommendations[] = ['Post-Purchase Email Series', 'First-Time Buyers', 'Medium', 'Low'];
        }

        if ($customerRetention['repeat_purchase_rate'] < 30) {
            $recommendations[] = ['Personalized Product Recommendations', 'Repeat Customers', 'High', 'Medium'];
        }

        if ($customerRetention['customer_ltv'] < 100) {
            $recommendations[] = ['Upsell/Cross-sell Campaigns', 'All Customers', 'Medium', 'Medium'];
        }

        if (empty($recommendations)) {
            $recommendations[] = ['Referral Program', 'Repeat Customers', 'High', 'Low'];
            $recommendations[] = ['Birthday/Anniversary Discounts', 'Loyal Customers', 'Medium', 'Low'];
        }

        foreach ($recommendations as $rec) {
            $data->push($rec);
        }

        return $data;
    }

    // Helper methods for formatting
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
