<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserProfileController extends Controller
{
    public function show(User $user)
    {
        $mixes = $user->mixes()
            ->where('is_public', true)
            ->withCount(['likes', 'sounds'])
            ->latest()
            ->get();

        $stats = [
            'mixes'      => $mixes->count(),
            'totalLikes' => $mixes->sum('likes_count'),
        ];

        return view('user.profile', compact('user', 'mixes', 'stats'));
    }
}
