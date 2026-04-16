<section>
    <header>
        <h2 class="text-lg font-medium text-slate-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            We'll send a 6-digit verification code to <span class="text-cyan-400">{{ auth()->user()->email }}</span>.
        </p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="mt-4 px-4 py-3 bg-emerald-950/60 border border-emerald-800 text-emerald-400 rounded-lg text-sm">
            Password updated successfully.
        </div>
    @endif

    <form method="POST" action="{{ route('profile.password.otp.send') }}" class="mt-6">
        @csrf
        <x-primary-button>Send Verification Code</x-primary-button>
    </form>
</section>
