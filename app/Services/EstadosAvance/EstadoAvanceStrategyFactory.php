<?php

namespace App\Services\EstadosAvance;

use Illuminate\Validation\ValidationException;

class EstadoAvanceStrategyFactory
{
    public function for(string $state): EstadoAvanceStrategy
    {
        return match ($state) {
            'En ejecución' => new EnEjecucionStrategy,
            'Finalizada' => new FinalizadaStrategy,
            'Continua' => new ContinuaStrategy,
            'No realizada' => new NoRealizadaStrategy,
            default => throw ValidationException::withMessages([
                'est' => 'Seleccione un estado de actividad válido.',
            ]),
        };
    }

    public function states(): array
    {
        return ['En ejecución', 'Finalizada', 'Continua', 'No realizada'];
    }
}
