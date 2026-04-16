<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mix;

class BookmarkController extends Controller
{
    public function index()
    {
        $mixes = Mix::whereHas('bookmarks', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with('user')
            ->withCount(['sounds', 'likes'])
            ->latest()
            ->get();

        return view('bookmarks', compact('mixes'));
    }

    public function toggleMix(Mix $mix)
    {
        $userId = auth()->id();

        $existing = $mix->bookmarks()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            $mix->bookmarks()->create(['user_id' => $userId]);
            $bookmarked = true;
        }

        return response()->json([
            'bookmarked' => $bookmarked,
        ]);
    }
}
