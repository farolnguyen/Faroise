<?php

namespace App\Http\Controllers;

use App\Models\Mix;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleMix(Mix $mix)
    {
        $userId = auth()->id();

        $existing = $mix->likes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $mix->likes()->create(['user_id' => $userId]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $mix->likes()->count(),
        ]);
    }
}
