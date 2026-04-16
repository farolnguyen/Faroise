@extends('admin.layout')
@section('pageTitle', 'Categories')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-slate-400">{{ $categories->count() }} categories</p>
    <a href="{{ route('admin.categories.create') }}"
       class="text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-4 py-2 rounded-lg transition-colors">
        + Add Category
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-800 text-left">
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Category</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Sounds</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Order</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse ($categories as $category)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $category->icon }}</span>
                        <span class="font-medium text-white">{{ $category->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-slate-400">{{ $category->sounds_count }}</td>
                <td class="px-4 py-3 text-slate-400">{{ $category->sort_order }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="text-xs text-slate-400 hover:text-cyan-400 transition-colors px-2.5 py-1 border border-slate-700 rounded-lg hover:border-cyan-700">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('Delete {{ addslashes($category->name) }}? This will affect all sounds in this category.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-400 px-2.5 py-1 border border-red-900/50 rounded-lg hover:border-red-700 transition-colors">
                                Del
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">No categories yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
