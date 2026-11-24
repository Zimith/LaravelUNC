<?php

namespace App\Services\EstadosAvance;

class FinalizadaStrategy implements EstadoAvanceStrategy
{
    public function rules(): array
    {
        return [
            'inicio' => ['nullable', 'date'],
            'fin' => ['nullable', 'date'],
        ];
    }

    public function fields(array $input): array
    {
        return [
            'inicio' => $input['inicio'] ?? '',
            'fin' => $input['fin'] ?? '',
            'justificacion' => '',
        ];
    }

    public function requiresFile(): bool
    {
        return true;
    }
}
