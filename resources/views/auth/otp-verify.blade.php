<x-guest-layout>
    <div class="mb-6 text-center">
        <p class="text-2xl font-bold text-white mb-1">✉️ Check your email</p>
        <p class="text-sm text-slate-400">We sent a 6-digit code to <span class="text-cyan-400">{{ $email }}</span></p>
    </div>

    <form method="POST" action="{{ route('password.otp.reset') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-5">
            <x-input-label for="otp" value="Verification Code" />
            <input id="otp" type="text" name="otp" inputmode="numeric" maxlength="6"
                autocomplete="one-time-code"
                class="mt-1 block w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-4 py-3 text-center text-3xl font-mono tracking-[0.5em] focus:outline-none focus:border-cyan-500 transition-colors"
                placeholder="000000" required autofocus>
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="mb-5">
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" type="password" name="password" class="mt-1 block w-full"
                required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                class="mt-1 block w-full" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">
            Reset Password
        </x-primary-button>

        <p class="mt-4 text-center text-sm text-slate-500">
            Didn't receive a code?
            <a href="{{ route('password.otp.request') }}" class="text-cyan-400 hover:text-cyan-300 transition-colors">Resend</a>
        </p>
    </form>
</x-guest-layout>
