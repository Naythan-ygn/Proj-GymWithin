<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-4">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">🏋️ Admin Dashboard</flux:heading>
            <flux:subheading>Real-time overview of users, inventory, orders & AI insights.</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button wire:click="refreshData" icon="arrow-path" variant="ghost">Refresh</flux:button>

            <flux:select wire:model.live="dateRange" class="w-40">
                <flux:select.option value="last_7_days">Last 7 Days</flux:select.option>
                <flux:select.option value="last_30_days">Last 30 Days</flux:select.option>
                <flux:select.option value="last_90_days">Last 90 Days</flux:select.option>
            </flux:select>

            <flux:button wire:click="exportTopProducts" variant="primary" icon="document-arrow-down"
                wire:loading.attr="disabled" wire:target="exportTopProducts" class="cursor-pointer">
                <span wire:loading.remove wire:target="exportTopProducts">Export to Excel</span>
                <span wire:loading wire:target="exportTopProducts">
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

    {{-- KPI Cards Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="glass-panel rounded-xl p-5 border-l-4 border-l-orange-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-zinc-500 text-sm">Total Members</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ $this->totalUsers() }}</p>
                    <p class="text-xs text-green-500 mt-1">+{{ $this->newUsersThisMonth() }} this month</p>
                </div>
                <flux:icon.users class="size-8 text-orange-500/70" />
            </div>
        </div>
        <div class="glass-panel rounded-xl p-5 border-l-4 border-l-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-zinc-500 text-sm">Total Products</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ $this->totalProducts() }}
                    </p>
                    <p class="text-xs text-zinc-500">{{ $this->lowStockCount() }} low stock items</p>
                </div>
                <flux:icon.shopping-bag class="size-8 text-blue-500/70" />
            </div>
        </div>
        <div class="glass-panel rounded-xl p-5 border-l-4 border-l-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-zinc-500 text-sm">Pending Orders</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white">{{ $this->pendingOrders() }}
                    </p>
                    <p class="text-xs text-zinc-500">Need attention</p>
                </div>
                <flux:icon.truck class="size-8 text-green-500/70" />
            </div>
        </div>
        <div class="glass-panel rounded-xl p-5 border-l-4 border-l-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-zinc-500 text-sm">Total Revenue</p>
                    <p class="text-3xl font-black text-zinc-900 dark:text-white">
                        ${{ number_format($this->totalRevenue(), 0) }}</p>
                    <p class="text-xs text-green-500">Last {{ $this->selectedDays }} days</p>
                </div>
                <flux:icon.currency-dollar class="size-8 text-purple-500/70" />
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-panel rounded-xl p-5">
            <h3 class="text-md font-bold mb-4">📈 Sales Trend</h3>
            <canvas wire:ignore id="salesChart" class="w-full h-64"></canvas>
        </div>
        <div class="glass-panel rounded-xl p-5">
            <h3 class="text-md font-bold mb-4">👤 New User Registrations</h3>
            <canvas wire:ignore id="usersChart" class="w-full h-64"></canvas>
        </div>
    </div>

    {{-- Main Grid: Tables & Analytics --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Orders Table --}}
        <div class="glass-panel rounded-xl overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                <h3 class="font-bold">🛒 Recent Orders</h3>
                <flux:button href="{{ route('admin.orders.index') }}" variant="ghost" size="sm" wire:navigate>
                    View
                    All</flux:button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-4 py-2 text-left">Order #</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-right">Total</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @php $recentOrders = $this->recentOrders(); @endphp
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 cursor-pointer"
                                wire:click="showOrder({{ $order->id }})">
                                <td class="px-4 py-2 font-mono text-orange-500">{{ $order->order_number }}</td>
                                <td class="px-4 py-2">{{ $order->user->name }}</td>
                                <td class="px-4 py-2 text-right font-semibold">
                                    ${{ number_format($order->total_price, 2) }}</td>
                                <td class="px-4 py-2 text-center">
                                    <flux:badge size="sm"
                                        color="{{ $order->status === 'pending' ? 'orange' : ($order->status === 'shipped' ? 'blue' : 'green') }}">
                                        {{ ucfirst($order->status) }}
                                    </flux:badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-zinc-500">No recent orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Critical Stock --}}
        <div class="glass-panel rounded-xl overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                <h3 class="font-bold">⚠️ Critical Stock (Runway < 7 days)</h3>
                        <flux:button href="{{ route('admin.inventory.index') }}" variant="ghost" size="sm"
                            wire:navigate>Manage Inventory</flux:button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                        <tr>
                            <th class="px-4 py-2 text-left">Product</th>
                            <th class="px-4 py-2 text-right">Stock</th>
                            <th class="px-4 py-2 text-right">Velocity</th>
                            <th class="px-4 py-2 text-center">Runway</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @php $criticalProducts = $this->criticalProducts(); @endphp
                        @forelse($criticalProducts as $product)
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="size-8 rounded bg-zinc-100 overflow-hidden"><img
                                                src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://placehold.co/100' }}"
                                                class="object-cover size-full"></div><span
                                            class="font-medium">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-right font-bold text-red-500">{{ $product->stock }}</td>
                                <td class="px-4 py-2 text-right">
                                    {{ number_format($product->sold_period / 30, 1) }}/day
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <flux:badge color="red">
                                        {{ floor($product->stock / max(0.01, ($product->sold_period / 30))) }} days
                                    </flux:badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-green-500">✅ No critical stock
                                    issues
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Most Asked Products --}}
        <div class="glass-panel rounded-xl p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold">🔥 Most Asked Products (Chatbot)</h3><flux:icon.chat-bubble-left-right
                    class="size-5 text-purple-500" />
            </div>
            <div class="space-y-3">
                @php $mostAsked = $this->mostAskedProducts(); @endphp
                @forelse($mostAsked as $product)
                    <div>
                        <div class="flex justify-between text-sm mb-1"><span>{{ $product['name'] }}</span><span
                                class="text-zinc-500">{{ $product['mentions'] }} mentions</span></div>
                        <div class="w-full bg-zinc-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full"
                                style="width: {{ ($product['mentions'] / max($mostAsked->pluck('mentions')->first() ?: 1, 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-zinc-500 text-center py-4">No chatbot data available</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Complaints --}}
        <div class="glass-panel rounded-xl p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold">⚠️ Recent Customer Complaints</h3><flux:icon.exclamation-triangle
                    class="size-5 text-red-500" />
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @php $complaints = $this->recentComplaints(); @endphp
                @forelse($complaints as $complaint)
                    <div class="text-sm p-2 bg-red-500/5 rounded-lg border-l-2 border-red-500">
                        <p class="line-clamp-2">{{ $complaint['message'] }}</p>
                        <p class="text-xs text-zinc-400 mt-1">
                            {{ \Carbon\Carbon::parse($complaint['date'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-zinc-500 text-center py-4">No recent complaints</p>
                @endforelse
            </div>
        </div>

        {{-- Top Selling Products --}}
        <div class="glass-panel rounded-xl overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <h3 class="font-bold">🏆 Top Selling Products (30d)</h3>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @php $topProducts = $this->topSellingProducts(); @endphp
                @forelse($topProducts as $product)
                    <div class="p-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded bg-zinc-100 overflow-hidden"><img
                                    src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://placehold.co/100' }}"
                                    class="object-cover size-full"></div>
                            <div>
                                <p class="font-medium">{{ $product->name }}</p>
                                <p class="text-xs text-zinc-500">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">{{ $product->total_sold }} sold</p>
                            <p class="text-xs text-zinc-500">${{ number_format($product->total_revenue, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500">No sales data yet</div>
                @endforelse
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="glass-panel rounded-xl overflow-hidden">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700 flex justify-between items-center">
                <h3 class="font-bold">👥 Recent Members</h3>
                <flux:button href="{{ route('admin.users.index') }}" variant="ghost" size="sm" wire:navigate>
                    Manage
                </flux:button>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @php $recentUsers = $this->recentUsers(); @endphp
                @forelse($recentUsers as $user)
                    <div class="p-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <flux:avatar :name="$user->name" size="sm" />
                            <div>
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-xs text-zinc-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <flux:badge :color="$user->role === 'admin' ? 'orange' : 'zinc'">{{ ucfirst($user->role) }}
                        </flux:badge>
                    </div>
                @empty
                    <div class="p-8 text-center text-zinc-500">No users found</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Order Detail Modal --}}
    <flux:modal name="super-order-modal" class="glass-modal-content w-full max-w-2xl">
        @if($selectedOrder)
            <div class="space-y-6 p-2">
                <div class="flex justify-between items-start">
                    <div>
                        <flux:heading size="lg">Order #{{ $selectedOrder->order_number }}</flux:heading>
                        <flux:subheading>{{ $selectedOrder->user->email }}</flux:subheading>
                    </div>
                    <flux:badge
                        :color="$selectedOrder->status === 'pending' ? 'orange' : ($selectedOrder->status === 'shipped' ? 'blue' : 'green')">
                        {{ ucfirst($selectedOrder->status) }}
                    </flux:badge>
                </div>
                <div class="bg-white/5 p-4 rounded-xl">
                    <p class="text-sm text-zinc-500">Shipping Address</p>
                    <p class="text-sm">{{ $selectedOrder->shipping_address }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-bold uppercase">Items</p>@foreach($selectedOrder->items as $item)<div
                        class="flex justify-between p-2 bg-white/5 rounded"><span>{{ $item->product->name }}
                            x{{ $item->quantity }}</span><span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>@endforeach
                </div>
                <div class="flex justify-between pt-4 border-t"><span class="font-bold">Total</span><span
                        class="text-xl font-black text-orange-500">${{ number_format($selectedOrder->total_price, 2) }}</span>
                </div>
            </div>
        @endif
    </flux:modal>

    @script
    <script>
        let salesChart, usersChart;
        const initCharts = () => {
            const ctxSales = document.getElementById('salesChart')?.getContext('2d');
            const ctxUsers = document.getElementById('usersChart')?.getContext('2d');
            if (ctxSales) {
                if (salesChart) salesChart.destroy();
                salesChart = new Chart(ctxSales, {
                    type: 'line',
                    data: {
                        labels: @json($this->chartLabels()),
                        datasets: [{
                            label: 'Sales ($)',
                            data: @json($this->salesData()),
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249,115,22,0.1)',
                            tension: 0.3,
                            fill: true
                        }]
                    }
                });
            }
            if (ctxUsers) {
                if (usersChart) usersChart.destroy();
                usersChart = new Chart(ctxUsers, {
                    type: 'bar',
                    data: {
                        labels: @json($this->chartLabels()),
                        datasets: [{
                            label: 'New Users',
                            data: @json($this->usersData()),
                            backgroundColor: '#3b82f6',
                            borderRadius: 8
                        }]
                    }
                });
            }
        };
        Livewire.hook('morph.updated', () => initCharts());
        document.addEventListener('livewire:navigated', () => initCharts());
        setTimeout(initCharts, 100);
    </script>
    @endscript
</div>
