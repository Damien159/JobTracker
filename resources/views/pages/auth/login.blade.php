<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        {{-- <div class="text-center"> <h1 class="text-2xl font-bold text-[#333333] dark:text-white"> Willkommen zurück </h1> <p class="mt-2 text-sm text-gray-500 dark:text-gray-400"> Melde dich an, um fortzufahren. </p> </div> --}}
        <x-auth-header :title="__('Willkommen zurück')" :description="__('Melde dich an, um fortzufahren.')" />

        {{-- Google Login --}}
        <div>
            <flux:button variant="outline" type="button" class="w-full">
                <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4"
                        d="M21.35 12.27c0-.71-.06-1.4-.18-2.05H12v3.88h5.23a4.47 4.47 0 0 1-1.94 2.93v2.44h3.14c1.84-1.69 2.92-4.18 2.92-7.2z" />
                    <path fill="#34A853"
                        d="M12 21.82c2.63 0 4.84-.87 6.45-2.35l-3.14-2.44c-.87.58-1.98.92-3.31.92-2.54 0-4.69-1.72-5.46-4.03H3.29v2.52A9.74 9.74 0 0 0 12 21.82z" />
                    <path fill="#FBBC05"
                        d="M6.54 13.92A5.86 5.86 0 0 1 6.23 12c0-.67.11-1.32.31-1.92V7.56H3.29A9.74 9.74 0 0 0 2.25 12c0 1.57.38 3.05 1.04 4.44l3.25-2.52z" />
                    <path fill="#EA4335"
                        d="M12 6.05c1.43 0 2.72.49 3.73 1.45l2.8-2.8C16.83 3.13 14.63 2.18 12 2.18a9.74 9.74 0 0 0-8.71 5.38l3.25 2.52C7.31 7.77 9.46 6.05 12 6.05z" />
                </svg>
                Mit Google anmelden
            </flux:button>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required
                autofocus autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" viewable />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Don\'t have an account?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
