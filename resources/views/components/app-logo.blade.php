@props([
    'sidebar' => false,
])

@if($sidebar)
    <a href="{{ route('dashboard') }}" wire:navigate {{ $attributes }} class="flex items-center gap-2 py-2">
        <flux:icon name="briefcase" class="size-7 text-brand-accent shrink-0" />
        <span class="text-xl font-bold tracking-tight">
            <span class="text-brand-accent">Job</span><span class="text-white">Tracker</span>
        </span>
    </a>
@else
    <a href="{{ route('dashboard') }}" wire:navigate {{ $attributes }} class="flex items-center gap-3">
        <flux:icon name="briefcase" class="size-9 text-brand-accent shrink-0" />
        <span class="text-2xl font-bold tracking-tight">
            <span class="text-brand-accent">Job</span><span class="text-white">Tracker</span>
        </span>
    </a>
@endif