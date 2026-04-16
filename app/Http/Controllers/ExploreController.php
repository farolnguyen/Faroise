<?php

namespace App\Http\Controllers;

use App\Models\Mix;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = Mix::where('is_public', true)
            ->with('user')
            ->withCount(['sounds', 'likes'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->latest();

        // Nếu user đã login, eager load trạng thái liked/bookmarked của họ
        if (auth()->check()) {
            $query->with([
                'likes' => fn ($q) => $q->where('user_id', auth()->id()),
                'bookmarks' => fn ($q) => $q->where('user_id', auth()->id()),
            ]);
        }

        $mixes = $query->paginate(12)->withQueryString();

        $featured = Mix::where('is_public', true)
            ->where('is_featured', true)
            ->with('user')
            ->withCount(['sounds', 'likes'])
            ->latest()
            ->take(4)
            ->get();

        return view('explore', compact('mixes', 'featured'));
    }
}
