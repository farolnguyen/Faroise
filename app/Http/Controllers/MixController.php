<?php

namespace App\Http\Controllers;

use App\Models\Mix;
use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MixController extends Controller
{
    /**
     * Danh sách mixes của user đang đăng nhập.
     */
    public function index()
    {
        $mixes = auth()->user()
            ->mixes()
            ->withCount('sounds')
            ->latest()
            ->get();

        return view('mixes.index', compact('mixes'));
    }

    /**
     * Lưu mix mới từ form.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public'   => ['boolean'],
            'sounds'      => ['required', 'array', 'min:1'],
            'sounds.*.id'     => ['required', 'exists:sounds,id'],
            'sounds.*.volume' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $mix = auth()->user()->mixes()->create([
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']) . '-' . Str::random(5),
            'description' => $data['description'] ?? null,
            'is_public'   => $request->boolean('is_public'),
        ]);

        // Attach sounds với volume vào bảng pivot mix_sounds
        $syncData = [];
        foreach ($data['sounds'] as $sound) {
            $syncData[$sound['id']] = ['volume' => $sound['volume']];
        }
        $mix->sounds()->sync($syncData);

        return response()->json([
            'success'  => true,
            'message'  => 'Mix "' . $mix->name . '" đã được lưu!',
            'mix_slug' => $mix->slug,
        ]);
    }

    /**
     * Xem chi tiết một mix công khai (hoặc của chính mình).
     */
    public function show(Mix $mix)
    {
        if (! $mix->is_public && $mix->user_id !== auth()->id()) {
            abort(403);
        }

        $mix->load('sounds', 'user')->loadCount(['likes', 'bookmarks']);

        $liked      = auth()->check() && $mix->likes()->where('user_id', auth()->id())->exists();
        $bookmarked = auth()->check() && $mix->bookmarks()->where('user_id', auth()->id())->exists();

        $related = Mix::where('user_id', $mix->user_id)
            ->where('id', '!=', $mix->id)
            ->where('is_public', true)
            ->withCount(['sounds', 'likes'])
            ->latest()
            ->take(3)
            ->get();

        return view('mixes.show', compact('mix', 'liked', 'bookmarked', 'related'));
    }

    /**
     * Cập nhật thông tin mix (name, description, visibility).
     */
    public function update(Request $request, Mix $mix)
    {
        if ($mix->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_public'   => ['boolean'],
        ]);

        $mix->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'is_public'   => $request->boolean('is_public'),
        ]);

        return response()->json([
            'success' => true,
            'mix'     => [
                'name'        => $mix->name,
                'description' => $mix->description,
                'is_public'   => $mix->is_public,
            ],
        ]);
    }

    /**
     * Xoá mix (chỉ owner mới được).
     */
    public function destroy(Mix $mix)
    {
        if ($mix->user_id !== auth()->id()) {
            abort(403);
        }

        $mix->sounds()->detach();
        $mix->delete();

        return redirect()->route('mixes.index')
            ->with('status', 'Mix đã được xoá.');
    }
}
