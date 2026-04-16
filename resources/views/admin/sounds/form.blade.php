@extends('admin.layout')
@section('pageTitle', isset($sound) ? 'Edit Sound' : 'Add Sound')

@section('content')

<div class="max-w-xl">
    <a href="{{ route('admin.sounds.index') }}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors mb-5 inline-block">← Back to Sounds</a>

    <div class="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <form method="POST" enctype="multipart/form-data"
              action="{{ isset($sound) ? route('admin.sounds.update', $sound) : route('admin.sounds.store') }}">
            @csrf
            @isset($sound) @method('PUT') @endisset

            {{-- Name --}}
            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Name <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $sound->name ?? '') }}"
                       class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                       placeholder="Rain, Ocean waves...">
                @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Icon --}}
            <div class="mb-4" x-data="{ iconMode: '{{ isset($sound) && $sound->icon_path ? 'image' : 'emoji' }}' }">
                <label class="block text-sm text-slate-400 mb-2">Icon <span class="text-red-400">*</span></label>
                <div class="flex gap-2 mb-3">
                    <button type="button" @click="iconMode = 'emoji'"
                        class="px-3 py-1 text-xs rounded-lg border transition-colors"
                        :class="iconMode === 'emoji' ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-600'">😀 Emoji</button>
                    <button type="button" @click="iconMode = 'image'"
                        class="px-3 py-1 text-xs rounded-lg border transition-colors"
                        :class="iconMode === 'image' ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-600'">🖼 Upload Image</button>
                </div>
                <div x-show="iconMode === 'emoji'" class="flex items-center gap-3">
                    <input type="text" name="icon" value="{{ old('icon', $sound->icon ?? '') }}"
                           class="w-28 bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-2xl text-center focus:outline-none focus:border-cyan-500"
                           placeholder="🌧️">
                    <span class="text-xs text-slate-600">Paste any emoji</span>
                </div>
                <div x-show="iconMode === 'image'">
                    <input type="file" name="icon_file" accept=".png,.jpg,.jpeg,.webp,.svg"
                           class="w-full text-sm text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-700 file:text-slate-300 hover:file:bg-slate-600 cursor-pointer">
                    @if(isset($sound) && $sound->icon_path)
                    <div class="flex items-center gap-3 mt-2">
                        <img src="{{ asset('storage/' . $sound->icon_path) }}" class="w-10 h-10 rounded object-contain bg-slate-800 p-1" alt="">
                        <span class="text-xs text-slate-600">Current icon — leave blank to keep</span>
                    </div>
                    @endif
                    @error('icon_file') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Color --}}
            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Color (hex) <span class="text-red-400">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="color" name="color" value="{{ old('color', $sound->color ?? '#6366f1') }}"
                           class="h-9 w-12 rounded cursor-pointer bg-slate-800 border border-slate-700">
                    <input type="text" id="colorText" value="{{ old('color', $sound->color ?? '#6366f1') }}"
                           class="flex-1 bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:border-cyan-500"
                           onchange="document.querySelector('[name=color]').value = this.value">
                </div>
                @error('color') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Audio Source --}}
            <div class="mb-4" x-data="{ audioMode: '{{ isset($sound) && $sound->source_type === 'local' ? 'file' : 'url' }}' }">
                <label class="block text-sm text-slate-400 mb-2">Audio Source <span class="text-red-400">*</span></label>
                <div class="flex gap-2 mb-3">
                    <button type="button" @click="audioMode = 'url'"
                        class="px-3 py-1 text-xs rounded-lg border transition-colors"
                        :class="audioMode === 'url' ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-600'">🔗 URL</button>
                    <button type="button" @click="audioMode = 'file'"
                        class="px-3 py-1 text-xs rounded-lg border transition-colors"
                        :class="audioMode === 'file' ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-600'">📁 Upload</button>
                </div>
                <div x-show="audioMode === 'url'">
                    <input type="url" name="audio_url" value="{{ old('audio_url', isset($sound) && $sound->source_type === 'external' ? $sound->external_url : '') }}"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                           placeholder="https://example.com/rain.mp3">
                    @error('audio_url') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div x-show="audioMode === 'file'">
                    <input type="file" name="audio_file" accept=".mp3,.ogg,.wav,.aac"
                           class="w-full text-sm text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-700 file:text-slate-300 hover:file:bg-slate-600 cursor-pointer">
                    @if(isset($sound) && $sound->source_type === 'local')
                    <p class="text-xs text-slate-600 mt-1">Current: {{ $sound->file_path }} — leave blank to keep</p>
                    @endif
                    @error('audio_file') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Loop Start --}}
            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-1">Loop Start <span class="text-slate-600">(seconds — 0 = loop from beginning)</span></label>
                <input type="number" name="loop_start" step="0.1" min="0"
                       value="{{ old('loop_start', $sound->loop_start ?? 0) }}"
                       class="w-32 bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500">
                @error('loop_start') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Sort Order --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Category <span class="text-red-400">*</span></label>
                    <select name="category_id"
                            class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500">
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $sound->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm text-slate-400 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $sound->sort_order ?? 0) }}" min="0"
                           class="w-full bg-slate-800 border border-slate-700 text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            {{-- Tags --}}
            <div class="mb-4">
                <label class="block text-sm text-slate-400 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach ($tags as $tag)
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input
                            type="checkbox"
                            name="tag_ids[]"
                            value="{{ $tag->id }}"
                            {{ (isset($sound) && $sound->tags->contains($tag->id)) || (is_array(old('tag_ids')) && in_array($tag->id, old('tag_ids'))) ? 'checked' : '' }}
                            class="rounded border-slate-600 bg-slate-700 text-cyan-500 focus:ring-cyan-500"
                        >
                        <span class="text-sm text-slate-300"># {{ $tag->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Active toggle --}}
            <div class="mb-6 flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active"
                       {{ old('is_active', $sound->is_active ?? true) ? 'checked' : '' }}
                       class="rounded border-slate-600 bg-slate-700 text-cyan-500 focus:ring-cyan-500">
                <label for="is_active" class="text-sm text-slate-400">Active (show on homepage)</label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-lg transition-colors">
                    {{ isset($sound) ? 'Update Sound' : 'Add Sound' }}
                </button>
                <a href="{{ route('admin.sounds.index') }}"
                   class="px-5 py-2 text-sm text-slate-400 border border-slate-700 rounded-lg hover:bg-slate-800 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('[name=color]').addEventListener('input', function() {
        document.getElementById('colorText').value = this.value;
    });
</script>

@endsection
