@extends('layouts.client-app')

@section('content')
    <div class="min-h-screen bg-black pt-32 pb-20 px-6" x-data="{ tab: 'profile' }">
        <div class="max-w-5xl mx-auto">
            <div class="mb-10 fade-in" data-fade>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">Account <span
                        class="gradient-text">Settings</span></h1>
                <p class="text-gray-400">Manage your retail account and security preferences.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <div class="lg:col-span-1 space-y-2 fade-in" data-fade>
                    <button @click="tab = 'profile'"
                        :class="tab === 'profile' ? 'bg-orange-500/10 text-orange-500 border-orange-500/20' : 'text-gray-400 border-transparent hover:bg-white/5'"
                        class="w-full text-left px-6 py-3 rounded-xl border font-medium transition-all outline-none">
                        Profile Info
                    </button>
                    <button @click="tab = 'security'"
                        :class="tab === 'security' ? 'bg-orange-500/10 text-orange-500 border-orange-500/20' : 'text-gray-400 border-transparent hover:bg-white/5'"
                        class="w-full text-left px-6 py-3 rounded-xl border font-medium transition-all outline-none">
                        Security
                    </button>
                    <div class="pt-4 mt-4 border-t border-white/10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-6 py-3 rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-500 border border-transparent transition-all font-medium">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-3 space-y-8">
                    <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="glass-card !m-0 rounded-3xl p-8">
                        <form action="{{ route('user.settings.update') }}" method="POST" class="space-y-8">
                            @csrf @method('PATCH')
                            <h3 class="text-xl font-semibold text-white border-b border-white/10 pb-4">Personal Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-400 ml-1">Full Name</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="contact-input"
                                        required>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-gray-400 ml-1">Email (Read-Only)</label>
                                    <div class="relative">
                                        <input type="email" value="{{ auth()->user()->email }}" readonly
                                            class="contact-input opacity-50 bg-gray-900 cursor-not-allowed">
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2">🔒</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="magnetic-btn bg-white text-black px-8 py-3 rounded-full font-bold hover:bg-orange-500 hover:text-white transition-all">
                                    Save Profile
                                </button>
                            </div>
                        </form>
                    </div>

                    <div x-show="tab === 'security'" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="glass-card !m-0 rounded-3xl p-8">
                        <form action="{{ route('user.password.update') }}" method="POST" class="space-y-6">
                            @csrf @method('PUT')
                            <h3 class="text-xl font-semibold text-white border-b border-white/10 pb-4">Update Password</h3>
                            <div class="space-y-4">
                                <div>
                                    <input type="password" name="current_password" placeholder="Current Password"
                                        class="contact-input" required>
                                    @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <input type="password" name="password" placeholder="New Password" class="contact-input"
                                        required>
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <input type="password" name="password_confirmation" placeholder="Confirm New Password"
                                    class="contact-input" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="magnetic-btn bg-orange-500 text-white px-8 py-3 rounded-full font-bold">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="min-h-screen bg-black pt-32 pb-20 px-6" x-data="{ tab: 'profile', showDeleteModal: false }">

                        <div class="p-8 border border-orange-500/20 rounded-3xl bg-orange-500/5 mt-8">
                            <h3 class="text-orange-500 font-bold mb-1">Danger Zone</h3>
                            <p class="text-gray-400 text-sm mb-4">Deleting your account will remove all order history and saved addresses.
                            </p>

                            <button @click="showDeleteModal = true" class="text-orange-500 hover:text-orange-400 underline font-medium text-sm">
                                Deactivate my GymWithin account
                            </button>
                        </div>

                        <template x-teleport="body">
                            <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center px-6" x-cloak>

                                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0" @click="showDeleteModal = false"
                                    class="absolute inset-0 bg-black/80 backdrop-blur-sm"></div>

                                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="relative bg-zinc-900 border border-white/10 w-full max-w-md p-8 rounded-3xl shadow-2xl overflow-hidden">

                                    <div class="text-center">
                                        <div
                                            class="w-16 h-16 bg-orange-500/10 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl">
                                            ⚠️
                                        </div>
                                        <h3 class="text-2xl font-bold text-white mb-2">Are you sure?</h3>
                                        <p class="text-gray-400 mb-8">This action is permanent and cannot be undone. You will lose access to
                                            all your purchases and data.</p>

                                        <div class="flex flex-col gap-3">
                                            <form action="{{ route('user.settings.destroy') }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-2xl transition-all">
                                                    Yes, Deactivate Account
                                                </button>
                                            </form>

                                            <button @click="showDeleteModal = false"
                                                class="w-full bg-white/5 hover:bg-white/10 text-white font-semibold py-4 rounded-2xl transition-all">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
