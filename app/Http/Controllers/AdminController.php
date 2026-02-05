<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importante para subir imágenes
use App\Models\SiteConfig;
use App\Models\News;

class AdminController extends Controller
{
    // Muestra el Dashboard
    public function index()
    {
        $user = Auth::user();

        // 1. Cargar Configuración (O crear una vacía si no existe)
        $config = SiteConfig::first();
        if (!$config) {
            $config = new SiteConfig();
        }

        // 2. Cargar Noticias (De la más nueva a la más vieja)
        $news = News::latest()->get();

        // 3. Decidir qué vista mostrar según el rol
        if ($user->nivel_usuario!== 1) {
            return view('admin_dashboard', compact('config', 'news'));
        }

        return view('dashboard', compact('config', 'news'));
    }

    // --- AQUÍ ESTABA EL PROBLEMA (Esta es la función real para guardar) ---
    public function updateConfig(Request $request)
    {
        // 1. Buscar la configuración
        $config = SiteConfig::first();
        if (!$config) {
            $config = new SiteConfig();
        }

        // 2. Guardar los textos (Conectamos los 'name' de tu HTML con la Base de Datos)
        $config->titulo_sitio = $request->input('site_title');
        
        // OJO: En tu HTML se llama 'footer_content', en la BD le pusimos 'footer_html'
        if ($request->has('footer_content')) {
            $config->footer_html = $request->input('footer_content');
        }

        // 3. Subir el Logo (Si seleccionaron uno nuevo)
        if ($request->hasFile('logo')) {
            // Borramos el logo viejo si existe para no llenar el disco
            if ($config->logo_path) {
                Storage::disk('public')->delete($config->logo_path);
            }
            
            // Guardamos el nuevo
            $path = $request->file('logo')->store('logos', 'public');
            $config->logo_path = $path;
        }

        // 4. Guardar cambios en MySQL
        $config->save();

        // 5. Redirigir con mensaje de éxito (Ya no dice Simulado)
        return back()->with('status', '¡Configuración guardada correctamente!');
    }

    // Guardar Nueva Noticia
    public function storeNews(Request $request)
    {
        // Validamos que al menos tenga título
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content'); // TinyMCE
        $news->video_url = $request->input('video_url');
        
        $news->save();

        return back()->with('status', '¡Noticia publicada con éxito!');
    }

    // Borrar Noticia
    public function deleteNews($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return back()->with('status', 'Noticia eliminada.');
    }
}