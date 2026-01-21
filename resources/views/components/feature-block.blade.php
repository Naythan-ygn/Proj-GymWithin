@props([
    'icon' => '🏆',
    'title' => 'Feature Title',
    'description' => 'Feature description goes here.'
])

<div {{ $attributes->merge(['class' => 'text-center fade-in']) }} data-fade>
    <div class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
        <span class="text-4xl">{{ $icon }}</span>
    </div>
    <h3 class="text-xl font-bold mb-3">{{ $title }}</h3>
    <p class="text-gray-400 text-sm leading-relaxed">
        {{ $description }}
    </p>
</div>