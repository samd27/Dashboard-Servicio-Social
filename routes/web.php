<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Models\SiteConfig;
use App\Models\News;
use Illuminate\Support\Facades\Route;

// --- RUTA PÚBLICA (Página de Inicio) ---
Route::get('/', function () {
    // 1. Obtener la configuración (Logo, Título, Footer)
    $config = SiteConfig::first();
    
    // 2. Obtener las noticias publicadas (ordenadas por la más reciente)
    $news = News::latest()->get();

    // 3. Mandar todo a la vista 'welcome'
    return view('welcome', compact('config', 'news'));
});

// --- RUTAS PRIVADAS (Requieren Login) ---
Route::middleware('auth')->group(function () {

    // 1. Dashboard (Panel de Administración)
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 2. Acciones del Administrador (Guardar Config y Noticias)
    Route::post('/admin/config', [AdminController::class, 'updateConfig'])->name('admin.config.update');
    Route::post('/admin/news', [AdminController::class, 'storeNews'])->name('admin.news.store');
    Route::delete('/admin/news/{id}', [AdminController::class, 'deleteNews'])->name('admin.news.delete');

    // 3. Rutas de Perfil (ESTAS ERAN LAS QUE FALTABAN)
    // Son necesarias para que funcione el menú desplegable de Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';