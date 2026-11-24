<?php

namespace App\Repositories;

use App\Models\Proyecto;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;

class ProyectoRepository
{
    public function asignadosA(Usuario $usuario): Collection
    {
        return $usuario->proyectos()->orderBy('proyectos.id')->get();
    }

    public function detalle(int $id): Proyecto
    {
        return Proyecto::query()
            ->with([
                'objetivosEspecificosLista.actividades.ultimoAvanceActivo',
                'indicadores',
            ])
            ->findOrFail($id);
    }

    public function actividades(int $proyectoId): Collection
    {
        return Proyecto::query()
            ->findOrFail($proyectoId)
            ->actividades()
            ->orderBy('id')
            ->get();
    }
}
