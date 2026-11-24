<?php

namespace App\Services\EstadosAvance;

class NoRealizadaStrategy implements EstadoAvanceStrategy
{
    public function rules(): array
    {
        return [
            'just' => ['required', 'string'],
        ];
    }

    public function fields(array $input): array
    {
        return [
            'inicio' => '',
            'fin' => '',
            'justificacion' => $input['just'],
        ];
    }

    public function requiresFile(): bool
    {
        return false;
    }
}
