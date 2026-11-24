<?php

namespace App\Services\EstadosAvance;

class ContinuaStrategy implements EstadoAvanceStrategy
{
    public function rules(): array
    {
        return [];
    }

    public function fields(array $input): array
    {
        return [
            'inicio' => '',
            'fin' => '',
            'justificacion' => '',
        ];
    }

    public function requiresFile(): bool
    {
        return true;
    }
}
