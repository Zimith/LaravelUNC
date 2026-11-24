<?php

namespace App\Services\EstadosAvance;

class EnEjecucionStrategy implements EstadoAvanceStrategy
{
    public function rules(): array
    {
        return ['inicio' => ['nullable', 'date']];
    }

    public function fields(array $input): array
    {
        return [
            'inicio' => $input['inicio'] ?? '',
            'fin' => '',
            'justificacion' => '',
        ];
    }

    public function requiresFile(): bool
    {
        return true;
    }
}
