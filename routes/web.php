<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SoundController as AdminSoundController;
use App\Http\Controllers\Admin\MixController as AdminMixController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MixController;
use App\Http\Controllers\OtpPasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
Route::get('/mix/{mix:slug}', [MixController::class, 'show'])->name('mixes.show');
Route::get('/u/{user}', [UserProfileController::class, 'show'])->name('user.profile');
Route::get('/screen', fn () => view('screen'))->name('screen');
Route::get('/offline', fn () => view('offline'))->name('offline');

Route::get('/dashboard', function () {
    return redirect()->route('mixes.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/password/otp', [OtpPasswordController::class, 'showForgotForm'])->name('password.otp.request');
    Route::post('/password/otp/send', [OtpPasswordController::class, 'sendForgotOtp'])->name('password.otp.send')->middleware('throttle:3,1');
    Route::get('/password/otp/verify', [OtpPasswordController::class, 'showForgotVerify'])->name('password.otp.verify');
    Route::post('/password/otp/reset', [OtpPasswordController::class, 'verifyForgotOtp'])->name('password.otp.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/mixes', [MixController::class, 'index'])->name('mixes.index');
    Route::post('/mixes', [MixController::class, 'store'])->name('mixes.store');
    Route::patch('/mixes/{mix}', [MixController::class, 'update'])->name('mixes.update');
    Route::delete('/mixes/{mix}', [MixController::class, 'destroy'])->name('mixes.destroy');

    Route::post('/likes/mix/{mix}', [LikeController::class, 'toggleMix'])->name('likes.mix');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/mix/{mix}', [BookmarkController::class, 'toggleMix'])->name('bookmarks.mix');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile/password/otp/send', [OtpPasswordController::class, 'sendProfileOtp'])->name('profile.password.otp.send')->middleware('throttle:3,1');
    Route::get('/profile/password/otp/verify', [OtpPasswordController::class, 'showProfileVerify'])->name('profile.password.verify');
    Route::post('/profile/password/otp/reset', [OtpPasswordController::class, 'verifyProfileOtp'])->name('profile.password.otp.reset');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('sounds', AdminSoundController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('users/{user}/role', [AdminUserController::class, 'toggleRole'])->name('users.role');
    Route::post('users/{user}/ban', [AdminUserController::class, 'toggleBan'])->name('users.ban');
    Route::get('mixes', [AdminMixController::class, 'index'])->name('mixes.index');
    Route::post('mixes/{mix}/featured', [AdminMixController::class, 'toggleFeatured'])->name('mixes.featured');
    Route::post('mixes/{mix}/visibility', [AdminMixController::class, 'toggleVisibility'])->name('mixes.visibility');
    Route::delete('mixes/{mix}', [AdminMixController::class, 'destroy'])->name('mixes.destroy');
});

require __DIR__.'/auth.php';
