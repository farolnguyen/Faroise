@extends('admin.layout')
@section('pageTitle', 'Dashboard')

@section('content')

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @foreach ([
        ['label' => 'Sounds',    'value' => $stats['sounds'],      'icon' => '🎵'],
        ['label' => 'Categories','value' => $stats['categories'],  'icon' => '🗂'],
        ['label' => 'Users',     'value' => $stats['users'],       'icon' => '👤'],
        ['label' => 'Mixes',     'value' => $stats['mixes'],       'icon' => '🎛'],
        ['label' => 'Public',    'value' => $stats['public_mixes'],'icon' => '🌐'],
    ] as $stat)
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
        <p class="text-2xl mb-1">{{ $stat['icon'] }}</p>
        <p class="text-2xl font-bold text-white">{{ $stat['value'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Recent Mixes --}}
<div class="bg-slate-900 border border-slate-800 rounded-xl">
    <div class="px-5 py-4 border-b border-slate-800 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-white">Recent Mixes</h2>
        <a href="{{ route('explore') }}" class="text-xs text-cyan-400 hover:text-cyan-300">View all →</a>
    </div>
    <div class="divide-y divide-slate-800">
        @forelse ($latestMixes as $mix)
        <div class="px-5 py-3 flex items-center justify-between">
            <div>
                <p class="text-sm text-white">{{ $mix->name }}</p>
                <p class="text-xs text-slate-500">by {{ $mix->user->name }} · {{ $mix->created_at->diffForHumans() }}</p>
            </div>
            <span class="text-xs {{ $mix->is_public ? 'text-cyan-400' : 'text-slate-600' }}">
                {{ $mix->is_public ? 'Public' : 'Private' }}
            </span>
        </div>
        @empty
        <p class="px-5 py-4 text-sm text-slate-500">No mixes yet.</p>
        @endforelse
    </div>
</div>

@endsection
