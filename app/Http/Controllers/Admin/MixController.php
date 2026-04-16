<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mix;

class MixController extends Controller
{
    public function index()
    {
        $mixes = Mix::with('user')
            ->withCount(['likes', 'sounds'])
            ->latest()
            ->paginate(20);

        return view('admin.mixes.index', compact('mixes'));
    }

    public function toggleFeatured(Mix $mix)
    {
        $mix->update(['is_featured' => ! $mix->is_featured]);

        return response()->json(['is_featured' => $mix->is_featured]);
    }

    public function toggleVisibility(Mix $mix)
    {
        $mix->update(['is_public' => ! $mix->is_public]);

        return response()->json(['is_public' => $mix->is_public]);
    }

    public function destroy(Mix $mix)
    {
        $mix->delete();

        return redirect()->route('admin.mixes.index')
            ->with('status', 'Mix "' . $mix->name . '" đã được xoá.');
    }
}
