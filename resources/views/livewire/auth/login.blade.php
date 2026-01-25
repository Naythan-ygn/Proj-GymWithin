<x-layouts::auth>
  <div
    x-data="{ show: false }"
    x-init="setTimeout(() => (show = true), 50)"
    class="grid overflow-hidden min-h-svh w-full bg-zinc-950 lg:grid-cols-2"
  >
    {{-- This is the left side --}}
    <div
      x-data="{ x: 0, y: 0 }"
      @mousemove="x = $event.clientX - $el.getBoundingClientRect().left; y = $event.clientY - $el.getBoundingClientRect().top"
      class="hidden flex-col overflow-hidden bg-zinc-950 relative items-center justify-center lg:flex"
    >
      <div
        class="z-0 pointer-events-none opacity-40 transition-all absolute -inset-[200px] duration-300 ease-out"
        :style="`background: radial-gradient(600px circle at ${x}px ${y}px, rgba(249, 115, 22, 0.2), transparent 80%)`"
      ></div>

      <div class="z-0 absolute inset-0">
        <div
          class="h-[50%] w-[50%] bg-zinc-800/40 rounded-full absolute bottom-[-10%] right-[-10%] blur-[80px]"
        ></div>
        <div
          class="opacity-[0.035] absolute inset-0 [background-image:linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] [background-size:40px_40px]"
        ></div>
      </div>

      <div class="z-10 flex flex-col relative items-center">
        <div
          x-show="show"
          x-transition:enter="transition ease-out duration-1000 delay-300"
          x-transition:enter-start="opacity-0 scale-90"
          x-transition:enter-end="opacity-100 scale-100"
        >
          <a
            href="{{ route("home") }}"
            wire:navigate
            class="block mb-12 group relative"
          >
            <div
              class="bg-orange-500/10 rounded-[2.5rem] opacity-0 absolute -inset-6 blur-2xl transition duration-500 group-hover:opacity-100"
            ></div>
            <div
              class="flex h-36 w-36 bg-white/5 rounded-[2rem] border border-white/10 shadow-2xl transition-all relative items-center justify-center backdrop-blur-3xl duration-500 group-hover:-translate-y-3"
            >
              <img
                src="{{ asset("Treadmill_Images/logo.png") }}"
                alt="Logo"
                class="h-20 w-auto"
              />
            </div>
          </a>
        </div>

        <div
          x-show="show"
          x-transition:enter="transition ease-out duration-1000 delay-500"
          x-transition:enter-start="opacity-0 translate-y-4"
          x-transition:enter-end="opacity-100 translate-y-0"
          class="text-center"
        >
          <h1
            class="text-6xl font-black tracking-tighter text-white uppercase italic"
          >
            Gym
            <span
              class="text-orange-500 drop-shadow-[0_0_15px_rgba(249,115,22,0.4)]"
            >
              Within
            </span>
          </h1>
          <p
            class="mt-4 text-xs font-bold tracking-[0.4em] text-zinc-600 uppercase dark:text-zinc-500"
          >
            Redefine Your Limits
          </p>
          <div
            class="grid grid-cols-3 mt-16 text-center opacity-30 transition-opacity gap-12 hover:opacity-60 duration-500"
          >
            <div class="flex flex-col items-center">
              <span class="text-xl font-bold text-white">01</span>
              <span class="text-[10px] tracking-widest text-zinc-400 uppercase">
                Track
              </span>
            </div>
            <div class="flex flex-col items-center">
              <span class="text-xl font-bold text-white">02</span>
              <span class="text-[10px] tracking-widest text-zinc-400 uppercase">
                Train
              </span>
            </div>
            <div class="flex flex-col items-center">
              <span class="text-xl font-bold text-white">03</span>
              <span class="text-[10px] tracking-widest text-zinc-400 uppercase">
                Transform
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- This is the Right side --}}
    <div
      x-show="show"
      x-transition:enter="transition ease-out duration-1000 transform"
      x-transition:enter-start="-translate-x-full opacity-0"
      x-transition:enter-end="translate-x-0 opacity-100"
      class="z-20 flex px-8 py-12 bg-zinc-50 border-r border-zinc-200 shadow-[20px_0_50px_rgba(0,0,0,0.5)] relative items-center justify-center dark:bg-zinc-900 dark:border-zinc-800 lg:px-16"
    >
      {{-- Back to Home Arrow --}}
      <a
        href="{{ route("home") }}"
        wire:navigate
        class="text-zinc-400 transition-colors absolute top-6 left-6 hover:text-orange-500 dark:text-zinc-600 dark:hover:text-orange-500"
        title="{{ __("Back to Home") }}"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          class="w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
          />
        </svg>
      </a>

      <div class="w-full max-w-sm space-y-8">
        <div class="space-y-2">
          <x-auth-header
            :title="__('Welcome Back')"
            :description="__('Enter your details to access GymWithin')"
          />
        </div>

        <x-auth-session-status :status="session('status')" />

        <form
          method="POST"
          action="{{ route("login.store") }}"
          class="flex flex-col gap-6"
          @submit="show = false"
          wire:submit="login"
        >
          @csrf

          <flux:input
            name="email"
            :label="__('Email')"
            type="email"
            placeholder="Your Email"
            required
            autofocus
          />

          <div class="relative">
            <flux:input
              name="password"
              :label="__('Password')"
              type="password"
              placeholder="Your Password"
              required
              viewable
            />
            @if (Route::has("password.request"))
              <flux:link
                class="text-xs font-semibold\ absolute top-0 end-0"
                :href="route('password.request')"
                variant="primary"
                wire:navigate
              >
                {{ __("Forgot?") }}
              </flux:link>
            @endif
          </div>
          <flux:checkbox name="remember" :label="__('Remember me')" />
          <flux:button
            variant="primary"
            type="submit"
            wire:loading.attr="disabled"
            class="flex overflow-hidden w-full h-12 shadow-lg shadow-orange-950/20 transition-all relative hover:bg-orange-500 active:scale-95 items-center justify-center"
          >
            <span wire:loading.remove wire:target="login">
              {{ __("Log in") }}
            </span>

            <span
              wire:loading
              wire:target="login"
              class="flex items-center gap-2"
            >
              <svg
                class="h-5 w-5 text-white animate-spin"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              <span>{{ __("Authenticating...") }}</span>
            </span>
          </flux:button>
        </form>

        @if (Route::has("register"))
          <div class="text-center text-sm text-zinc-500 dark:text-zinc-400">
            {{ __('Don\'t have an account?') }}
            <flux:link
              :href="route('register')"
              class="font-semibold"
              wire:navigate
              variant="primary"
            >
              {{ __("Join now") }}
            </flux:link>
          </div>
        @endif
      </div>
    </div>
  </div>    
</x-layouts::auth>
