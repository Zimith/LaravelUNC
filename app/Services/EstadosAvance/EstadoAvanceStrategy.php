<?php

namespace App\Services\EstadosAvance;

interface EstadoAvanceStrategy
{
    public function rules(): array;

    public function fields(array $input): array;

    public function requiresFile(): bool;
}
