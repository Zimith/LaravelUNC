<?php

namespace App\Repositories;

use App\Models\Actividad;
use App\Models\GradoAvance;
use Illuminate\Database\Eloquent\Collection;

class AvanceRepository
{
    public function porProyecto(int $proyectoId): Collection
    {
        return GradoAvance::query()
            ->with('actividad')
            ->where('proyecto', $proyectoId)
            ->where('activo', 1)
            ->orderByDesc('fecha')
            ->get();
    }

    public function ultimoActivoPorActividad(int $actividadId): ?GradoAvance
    {
        return GradoAvance::query()
            ->where('actividad', $actividadId)
            ->where('activo', 1)
            ->orderByDesc('id')
            ->first();
    }

    public function registrar(array $attributes): GradoAvance
    {
        return GradoAvance::query()->create($attributes);
    }

    public function registrarVarios(array $avances): void
    {
        foreach ($avances as $avance) {
            $this->registrar($avance);
        }
    }

    public function eliminarLogicamente(int $proyectoId, int $avanceId): ?GradoAvance
    {
        $avance = GradoAvance::query()
            ->where('proyecto', $proyectoId)
            ->where('activo', 1)
            ->find($avanceId);

        if ($avance) {
            $avance->activo = 0;
            $avance->save();
        }

        return $avance;
    }

    public function actividadDelProyecto(int $proyectoId, int $actividadId): ?Actividad
    {
        return Actividad::query()
            ->where('proyecto', $proyectoId)
            ->find($actividadId);
    }
}
