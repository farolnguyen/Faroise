@extends('admin.layout')
@section('pageTitle', isset($category) ? 'Edit Category' : 'Add Category')

@section('content')

<div class="max-w-sm">
    <a href="{{ route('admin.categories.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors mb-5 inline-block">← Back to Categories</a>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <form method="POST"
              action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @isset($category) @method('PUT') @endisset

            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                       placeholder="Nature, Urban...">
                @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Icon (emoji) <span class="text-red-400">*</span></label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                       placeholder="🌿">
                @error('icon') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm text-slate-400 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500">
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-lg transition-colors">
                    {{ isset($category) ? 'Update' : 'Add Category' }}
                </button>
                <a href="{{ route('admin.categories.index') }}"
                   class="px-5 py-2 text-sm text-slate-400 border border-slate-700 rounded-lg hover:bg-slate-800 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
