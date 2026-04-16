<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Sound;
use App\Models\Tag;
use App\Http\Requests\Admin\SoundRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SoundController extends Controller
{
    public function index()
    {
        $sounds = Sound::with('category')->orderBy('category_id')->orderBy('sort_order')->get();
        return view('admin.sounds.index', compact('sounds'));
    }

    public function create()
    {
        $categories = Category::orderBy('sort_order')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.sounds.form', compact('categories', 'tags'));
    }

    public function store(SoundRequest $request)
    {
        $data = $request->only(['name', 'icon', 'color', 'category_id', 'sort_order', 'loop_start']);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug']      = Str::slug($request->input('name'));

        if ($request->hasFile('icon_file')) {
            $ifile = $request->file('icon_file');
            $iext  = $ifile->getClientOriginalExtension() ?: 'png';
            $iname = Str::uuid() . '.' . ltrim($iext, '.');
            $idest = storage_path('app/public/icons');
            if (!is_dir($idest)) mkdir($idest, 0755, true);
            $ifile->move($idest, $iname);
            $data['icon_path'] = 'icons/' . $iname;
            $data['icon']      = null;
        }

        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $ext  = $file->getClientOriginalExtension() ?: 'mp3';
            $name = Str::uuid() . '.' . ltrim($ext, '.');
            $dest = storage_path('app/public/sounds');
            if (!is_dir($dest)) mkdir($dest, 0755, true);
            $file->move($dest, $name);
            $data['file_path']    = 'sounds/' . $name;
            $data['source_type']  = 'local';
            $data['external_url'] = null;
        } else {
            $data['external_url'] = $request->input('audio_url');
            $data['source_type']  = 'external';
            $data['file_path']    = null;
        }

        $sound = Sound::create($data);
        $sound->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.sounds.index')
            ->with('status', 'Sound "' . $data['name'] . '" đã được thêm.');
    }

    public function edit(Sound $sound)
    {
        $categories = Category::orderBy('sort_order')->get();
        $tags = Tag::orderBy('name')->get();
        $sound->load('tags');
        return view('admin.sounds.form', compact('sound', 'categories', 'tags'));
    }

    public function update(SoundRequest $request, Sound $sound)
    {
        $data = $request->only(['name', 'icon', 'color', 'category_id', 'sort_order', 'loop_start']);
        $data['is_active'] = $request->boolean('is_active');
        $data['slug']      = Str::slug($request->input('name'));

        if ($request->hasFile('icon_file')) {
            if ($sound->icon_path) {
                @unlink(storage_path('app/public/' . $sound->icon_path));
            }
            $ifile = $request->file('icon_file');
            $iext  = $ifile->getClientOriginalExtension() ?: 'png';
            $iname = Str::uuid() . '.' . ltrim($iext, '.');
            $idest = storage_path('app/public/icons');
            if (!is_dir($idest)) mkdir($idest, 0755, true);
            $ifile->move($idest, $iname);
            $data['icon_path'] = 'icons/' . $iname;
            $data['icon']      = null;
        } elseif ($request->filled('icon')) {
            $data['icon_path'] = null;
        }

        if ($request->hasFile('audio_file')) {
            if ($sound->source_type === 'local' && $sound->file_path) {
                @unlink(storage_path('app/public/' . $sound->file_path));
            }
            $file = $request->file('audio_file');
            $ext  = $file->getClientOriginalExtension() ?: 'mp3';
            $name = Str::uuid() . '.' . ltrim($ext, '.');
            $dest = storage_path('app/public/sounds');
            if (!is_dir($dest)) mkdir($dest, 0755, true);
            $file->move($dest, $name);
            $data['file_path']    = 'sounds/' . $name;
            $data['source_type']  = 'local';
            $data['external_url'] = null;
        } elseif ($request->filled('audio_url')) {
            $data['external_url'] = $request->input('audio_url');
            $data['source_type']  = 'external';
            $data['file_path']    = null;
        }

        $sound->update($data);
        $sound->tags()->sync($request->input('tag_ids', []));

        return redirect()->route('admin.sounds.index')
            ->with('status', 'Sound "' . $sound->name . '" đã được cập nhật.');
    }

    public function destroy(Sound $sound)
    {
        $sound->delete();
        return redirect()->route('admin.sounds.index')
            ->with('status', 'Sound đã được xoá.');
    }
}
