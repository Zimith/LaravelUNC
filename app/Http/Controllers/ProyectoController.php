<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Usuario;
use App\Repositories\ProyectoRepository;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function __construct(private readonly ProyectoRepository $proyectos) {}

    public function index(): View
    {
        $usuario = Usuario::query()->findOrFail(session('user_id'));

        return view('projects.index', [
            'usuario' => $usuario,
            'proyectos' => $this->proyectos->asignadosA($usuario),
        ]);
    }

    public function show(Proyecto $proyecto): View
    {
        $proyecto = $this->proyectos->detalle($proyecto->id);

        return view('projects.show', [
            'proyecto' => $proyecto,
            'tipoUsuario' => (int) session('user_type'),
        ]);
    }
}
