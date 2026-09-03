<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div
            class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
            <div class="absolute inset-0 bg-blue-950"></div>
            <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-2 text-lg font-medium" wire:navigate>
                <span class="flex h-10 w-10 items-center justify-center rounded-md">
                    <x-app-logo-icon class="h-7 fill-current text-white" />
                </span>
                JobTracker
            </a>

            <div class="relative z-20 mt-auto space-y-6">
                <div class="space-y-2">
                    <flux:heading size="xl">{{ __('Behalte den Überblick über deine Karriere.') }}</flux:heading>
                    <p class="text-blue-100">
                        {{ __('Verwalte all deine Bewerbungen an einem Ort. Nie mehr den Überblick verlieren.') }}</p>
                </div>

                <ul class="space-y-3 text-sm text-blue-100">
                    <li class="flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4 shrink-0" />
                        {{ __('Bewerbungen zentral verwalten') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4 shrink-0" />
                        {{ __('Termine & Erinnerungen im Blick') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="check-circle" class="size-4 shrink-0" />
                        {{ __('Statistiken zu deinem Bewerbungserfolg') }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden"
                    wire:navigate>
                    <span class="flex h-9 w-9 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>

                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
