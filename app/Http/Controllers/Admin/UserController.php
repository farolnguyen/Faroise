<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['mixes', 'likes'])
            ->orderBy('created_at')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot change your own role.'], 403);
        }

        $user->update([
            'role' => $user->role === 'admin' ? 'user' : 'admin',
        ]);

        return response()->json([
            'role' => $user->role,
        ]);
    }

    public function toggleBan(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot ban yourself.'], 403);
        }

        $user->update([
            'banned_at' => $user->isBanned() ? null : Carbon::now(),
        ]);

        return response()->json([
            'banned' => $user->isBanned(),
        ]);
    }
}
