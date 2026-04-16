<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-2xl font-bold text-white mb-1">🔐 Reset Password</p>
        <p class="text-sm text-slate-400">Enter your email and we'll send you a 6-digit verification code.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-950/60 border border-emerald-800 text-emerald-400 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.otp.send') }}">
        @csrf

        <div class="mb-5">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" class="mt-1 block w-full"
                :value="old('email')" required autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">
            Send Verification Code
        </x-primary-button>

        <p class="mt-5 text-center text-sm text-slate-500">
            Remember your password?
            <a href="{{ route('login') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors">Log in</a>
        </p>
    </form>
</x-guest-layout>
