@if (session('status'))
    <div class="mb-5 px-4 py-3 bg-emerald-950/60 border border-emerald-800 text-emerald-400 rounded-lg text-sm">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-5 px-4 py-3 bg-red-950/60 border border-red-800 text-red-400 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif
