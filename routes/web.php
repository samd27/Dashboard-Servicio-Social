<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\SiteConfig;
use App\Models\News;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $config = SiteConfig::first();
    $news = News::latest()->get();

    return view('welcome', compact('config', 'news'));
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::middleware('role:0')->group(function () {
        Route::post('/admin/config', [AdminController::class, 'updateConfig'])->name('admin.config.update');
        Route::post('/admin/news', [AdminController::class, 'storeNews'])->name('admin.news.store');
        Route::delete('/admin/news/{id}', [AdminController::class, 'deleteNews'])->name('admin.news.delete');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
