<x-app-layout>
    <x-slot name="title">Change Password</x-slot>

    <div class="max-w-xl mx-auto px-4 py-12">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-8">
            <div class="mb-6 text-center">
                <p class="text-xl font-bold text-white mb-1">✉️ Check your email</p>
                <p class="text-sm text-slate-400">We sent a 6-digit code to
                    <span class="text-cyan-400">{{ auth()->user()->email }}</span></p>
            </div>

            @if (session('otp_sent'))
                <div class="mb-5 px-4 py-3 bg-emerald-950/60 border border-emerald-800 text-emerald-400 rounded-lg text-sm text-center">
                    Verification code sent!
                </div>
            @endif

            <form method="POST" action="{{ route('profile.password.otp.reset') }}">
                @csrf

                <div class="mb-5">
                    <label for="otp" class="block text-sm font-medium text-slate-400 mb-1">Verification Code</label>
                    <input id="otp" type="text" name="otp" inputmode="numeric" maxlength="6"
                        autocomplete="one-time-code"
                        class="block w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-4 py-3 text-center text-3xl font-mono tracking-[0.5em] focus:outline-none focus:border-cyan-500 transition-colors"
                        placeholder="000000" required autofocus>
                    @error('otp')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-slate-400 mb-1">New Password</label>
                    <input id="password" type="password" name="password"
                        class="block w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500 transition-colors"
                        required autocomplete="new-password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="block w-full bg-slate-800 border border-slate-700 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500 transition-colors"
                        required autocomplete="new-password">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold rounded-lg transition-colors">
                    Update Password
                </button>

                <a href="{{ route('profile.edit') }}"
                    class="block mt-4 text-center text-sm text-slate-500 hover:text-slate-300 transition-colors">
                    ← Back to profile
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
