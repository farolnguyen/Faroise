<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Mix;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['sounds' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order')->with('tags');
        }])
        ->orderBy('sort_order')
        ->get()
        ->filter(fn ($category) => $category->sounds->isNotEmpty());

        $tags = Tag::has('sounds')->orderBy('name')->get();

        $allSounds = $categories->flatMap(fn ($c) => $c->sounds)->values();

        // Nếu có ?mix=slug trên URL, load mix đó để Alpine auto-play
        $mixData = null;
        if ($slug = $request->query('mix')) {
            $mix = Mix::where('slug', $slug)
                ->where(function ($q) {
                    $q->where('is_public', true)
                      ->orWhere('user_id', auth()->id());
                })
                ->with('sounds')
                ->first();

            if ($mix) {
                $mixData = [
                    'name'   => $mix->name,
                    'sounds' => $mix->sounds->map(fn ($s) => [
                        'id'     => $s->id,
                        'url'    => $s->audio_url,
                        'volume' => $s->pivot->volume,
                    ])->values(),
                ];
            }
        }

        return view('home', compact('categories', 'tags', 'allSounds', 'mixData'));
    }
}
