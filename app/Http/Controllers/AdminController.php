<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; //Guardar imagenes
use Illuminate\Support\Facades\Hash; //Hasheo de contraseñas
use App\Models\SiteConfig;
use App\Models\News;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Bloqueo de seguridad para no Admins
        if ($user->nivel_usuario !== 0) {
            return redirect('/');
        }

        $config = SiteConfig::first() ?? new SiteConfig();
        $news = News::latest()->get();

        // --- NUEVO: Traer a todos los usuarios MENOS a mí mismo (el admin) ---
        $users = User::where('id', '!=', $user->id)->get();

        // Enviamos 'users' a la vista
        return view('dashboard', compact('config', 'news', 'users'));
    }

    public function updateConfig(Request $request)
    {
        $config = SiteConfig::first() ?? new SiteConfig();
        $config->titulo_sitio = $request->input('site_title');

        if ($request->has('footer_content')) {
            $config->footer_html = $request->input('footer_content');
        }

        if ($request->hasFile('logo')) {
            if ($config->logo_path) Storage::disk('public')->delete($config->logo_path);
            $config->logo_path = $request->file('logo')->store('logos', 'public');
        }

        $config->save();
        return back()->with('status', 'Configuración actualizada.');
    }

    public function storeNews(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->video_url = $request->input('video_url');
        $news->save();

        return back()->with('status', 'Noticia publicada.');
    }

    public function deleteNews($id)
    {
        News::findOrFail($id)->delete();
        return back()->with('status', 'Noticia eliminada.');
    }

    // --- NUEVAS FUNCIONES DE GESTIÓN DE USUARIOS ---

    // 1. Crear Nuevo Usuario
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'nivel_usuario' => 'required|integer|in:0,1,2', // 0:Admin, 1:Distrib, 2:Cliente
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nivel_usuario' => $request->nivel_usuario,
            'vigente' => true, // Nacen activos
        ]);

        return back()->with('status', 'Usuario registrado correctamente.');
    }

    // 2. Activar / Desactivar (El botón de poder)
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        // Invertimos el valor (Si es 1 pasa a 0, si es 0 pasa a 1)
        $user->vigente = !$user->vigente;
        $user->save();

        $status = $user->vigente ? 'activado' : 'desactivado';
        return back()->with('status', "El usuario ha sido $status.");
    }

    // 3. Eliminar usuario permanentemente
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('status', 'Usuario eliminado permanentemente.');
    }
}
