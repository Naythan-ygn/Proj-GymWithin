<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div>
            <flux:heading size="xl">AI Analytics Dashboard</flux:heading>
            <flux:subheading>Intelligent insights powered by AI analysis</flux:subheading>
        </div>

        <div class="flex gap-3">
            <flux:select wire:model.live="dateRange" class="w-40">
                <flux:select.option value="last_7_days">Last 7 Days</flux:select.option>
                <flux:select.option value="last_30_days">Last 30 Days</flux:select.option>
                <flux:select.option value="last_90_days">Last 90 Days</flux:select.option>
                <flux:select.option value="this_year">This Year</flux:select.option>
            </flux:select>

            <flux:button wire:click="exportToExcel" variant="primary" icon="document-arrow-down"
                wire:loading.attr="disabled" wire:target="exportToExcel" class="cursor-pointer">
                <span wire:loading.remove wire:target="exportToExcel">Export to Excel</span>
                <span wire:loading wire:target="exportToExcel">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Generating...
                </span>
            </flux:button>
        </div>
    </div>

    {{-- Insight Content - Grid Layout --}}
    @php
        $content = $aiInsights['content'] ?? '';
        $sections = preg_split('/\n\s*\n/', trim($content));

        $first = count($sections) > 0 ? array_shift($sections) : null;
        $last = count($sections) > 0 ? array_pop($sections) : null;
    @endphp

    {{-- Subtitle (First Section) --}}
    @if ($first)
        <div class="mb-4 px-1">
            <p class="text-sm font-semibold text-purple-500">
                {{ trim(str_replace('###', '', $first)) }}
            </p>
        </div>
    @endif

    {{-- Main Insight Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($sections as $section)
            @php
                $lines = explode("\n", trim($section));
                $title = array_shift($lines);
            @endphp

            <div class="relative rounded-xl border border-zinc-200 dark:border-zinc-700
                            bg-zinc-50 dark:bg-zinc-800/50 p-4 overflow-hidden
                            hover:-translate-y-0.5 transition-transform duration-200">

                {{-- Hover Gradient --}}
                <div class="absolute inset-0 rounded-xl opacity-0 hover:opacity-100 transition
                                bg-gradient-to-r from-purple-500/10 via-pink-500/10 to-indigo-500/10">
                </div>

                {{-- Left Accent --}}
                <div class="absolute left-0 top-0 h-full w-1
                                bg-gradient-to-b from-purple-500 to-pink-500 rounded-l-xl">
                </div>

                <div class="relative z-10 pl-2">
                    {{-- Title --}}
                    <h4 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100 mb-2">
                        {{ $title }}
                    </h4>

                    {{-- Content --}}
                    <ul class="space-y-1">
                        @foreach ($lines as $line)
                            <li class="text-sm text-zinc-700 dark:text-zinc-300 flex items-start gap-2">
                                <span class="mt-1 w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                <span>{{ ltrim($line, '- ') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer (Last Section) --}}
    @if ($last)
        <div class="mt-5 p-4 rounded-xl bg-purple-500/5 border border-purple-500/10">
            <p class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ $last }}
            </p>
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mx-4">

        {{-- Most Asked Products Card --}}
        <div class="glass-panel rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="p-5 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">🔥 Most Asked Products</h3>
                <p class="text-xs text-zinc-500 mt-1">Products frequently mentioned in conversations</p>
            </div>

            <div class="p-5">
                @if ($mostAskedProducts['mentioned']->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($mostAskedProducts['mentioned']->take(5) as $productName => $mentionCount)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $productName }}</span>
                                    <span class="text-zinc-500">{{ $mentionCount }} mentions</span>
                                </div>
                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full"
                                        style="width: {{ ($mentionCount / $mostAskedProducts['mentioned']->first()) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($mostAskedProducts['top_selling']->isNotEmpty())
                            <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                                <p class="text-xs font-semibold text-zinc-500 mb-3">📦 Top Selling (Same Period)</p>
                                @foreach ($mostAskedProducts['top_selling']->take(3) as $product)
                                    <div class="flex justify-between items-center py-2">
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                {{ $product['name'] }}
                                            </p>
                                            <p class="text-xs text-zinc-500">SKU: {{ $product['sku'] }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                {{ $product['quantity_sold'] }} sold
                                            </p>
                                            <p class="text-xs text-zinc-500">
                                                ${{ number_format($product['revenue'], 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-center text-zinc-500 py-8">No product mentions found</p>
                @endif
            </div>
        </div>

        {{-- Customer Complaints Card --}}
        <div class="glass-panel rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="p-5 border-b border-neutral-200 dark:border-neutral-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">⚠️ Customer Complaints</h3>
                        <p class="text-xs text-zinc-500 mt-1">Issue categorization and volume</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-red-500">{{ $customerComplaints['total_complaints'] }}</p>
                        <p class="text-xs text-zinc-500">{{ $customerComplaints['complaint_rate'] }}% of conversations
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-5">
                {{-- Pie Chart Style Distribution --}}
                <div class="space-y-3 mb-6">
                    @foreach ($customerComplaints['by_category'] as $category => $count)
                        @if ($count > 0)
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $category }}</span>
                                    <span class="text-zinc-500">{{ $count }} complaints</span>
                                </div>
                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full"
                                        style="width: {{ ($count / max($customerComplaints['by_category'])) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Recent Complaints List --}}
                @if ($customerComplaints['recent_complaints']->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                        <p class="text-xs font-semibold text-zinc-500 mb-3">📝 Recent Complaints</p>
                        <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach ($customerComplaints['recent_complaints'] as $complaint)
                                <div class="text-sm p-2 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                                    <p class="text-zinc-700 dark:text-zinc-300 line-clamp-2">
                                        {{ $complaint['message'] }}
                                    </p>
                                    <p class="text-xs text-zinc-400 mt-1">{{ $complaint['date']->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Chatbot Usage Card --}}
        <div class="glass-panel rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="p-5 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">💬 Chatbot Usage</h3>
                <p class="text-xs text-zinc-500 mt-1">User engagement and conversation metrics</p>
            </div>

            <div class="p-5">
                {{-- Key Metrics --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-500">{{ $chatbotUsage['unique_sessions'] }}</p>
                        <p class="text-xs text-zinc-500">Conversations</p>
                    </div>
                    <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-500">{{ $chatbotUsage['avg_messages_per_session'] }}
                        </p>
                        <p class="text-xs text-zinc-500">Avg Messages/Session</p>
                    </div>
                </div>

                {{-- Session Length Distribution --}}
                <div class="mb-6">
                    <p class="text-xs font-semibold text-zinc-500 mb-2">Session Length Distribution</p>
                    <div class="space-y-2">
                        @foreach ($chatbotUsage['session_length_distribution'] as $range => $count)
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-zinc-700 dark:text-zinc-300">{{ $range }}</span>
                                    <span class="text-zinc-500">{{ $count }} sessions</span>
                                </div>
                                <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-500 h-2 rounded-full"
                                        style="width: {{ $chatbotUsage['unique_sessions'] > 0 ? ($count / $chatbotUsage['unique_sessions']) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Peak Hours --}}
                <div class="pt-4 border-t border-neutral-200 dark:border-neutral-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-xs text-zinc-500">Peak Usage Time</p>
                            <p class="text-lg font-bold text-zinc-900 dark:text-zinc-100">
                                {{ $chatbotUsage['peak_hours']['peak_hour'] }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-zinc-500">Messages at peak</p>
                            <p class="text-lg font-bold text-blue-500">
                                {{ $chatbotUsage['peak_hours']['peak_hour_count'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer Retention Card --}}
        <div class="glass-panel rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="p-5 border-b border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100">👥 Customer Retention</h3>
                <p class="text-xs text-zinc-500 mt-1">Loyalty and repeat purchase metrics</p>
            </div>

            <div class="p-5">
                {{-- Key Metrics --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-2xl font-bold text-green-500">{{ $customerRetention['retention_rate'] }}%</p>
                        <p class="text-xs text-zinc-500">Retention Rate</p>
                    </div>
                    <div class="text-center p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg">
                        <p class="text-2xl font-bold text-green-500">{{ $customerRetention['repeat_purchase_rate'] }}%
                        </p>
                        <p class="text-xs text-zinc-500">Repeat Purchase Rate</p>
                    </div>
                </div>

                {{-- Customer Segments --}}
                <div class="mb-6">
                    <p class="text-xs font-semibold text-zinc-500 mb-2">Customer Segments</p>
                    <div class="space-y-2">
                        <div
                            class="flex justify-between items-center py-2 border-b border-neutral-200 dark:border-neutral-700">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">New Customers</span>
                            <span
                                class="text-sm font-semibold text-blue-500">{{ $customerRetention['new_customers'] }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-2 border-b border-neutral-200 dark:border-neutral-700">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">First-Time Buyers</span>
                            <span
                                class="text-sm font-semibold text-orange-500">{{ $customerRetention['first_time_buyers'] }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-2 border-b border-neutral-200 dark:border-neutral-700">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">Repeat Customers</span>
                            <span
                                class="text-sm font-semibold text-green-500">{{ $customerRetention['repeat_customers'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">Avg. Customer LTV</span>
                            <span
                                class="text-sm font-semibold text-purple-500">${{ number_format($customerRetention['customer_ltv'], 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Retention by Cohort (Last 3 months) --}}
                @if (!empty($customerRetention['retention_by_cohort']))
                    <div class="pt-4 border-t border-neutral-200 dark:border-neutral-700">
                        <p class="text-xs font-semibold text-zinc-500 mb-2">Retention by Cohort</p>
                        <div class="space-y-2">
                            @foreach (array_slice($customerRetention['retention_by_cohort'], -3, 3, true) as $cohort => $retention)
                                <div class="text-xs">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-zinc-500">{{ $cohort }}</span>
                                        <span class="text-green-500">{{ $retention['month_1'] ?? 0 }}% retained</span>
                                    </div>
                                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-1.5">
                                        <div class="bg-green-500 h-1.5 rounded-full"
                                            style="width: {{ $retention['month_1'] ?? 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
