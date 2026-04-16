<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Mix;
use App\Models\Sound;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'sounds'     => Sound::count(),
            'categories' => Category::count(),
            'users'      => User::count(),
            'mixes'      => Mix::count(),
            'public_mixes' => Mix::where('is_public', true)->count(),
        ];

        $latestMixes = Mix::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'latestMixes'));
    }
}
