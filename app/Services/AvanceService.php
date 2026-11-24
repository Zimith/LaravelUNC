<?php

namespace App\Services;

use App\Repositories\AvanceRepository;
use App\Services\EstadosAvance\EstadoAvanceStrategy;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class AvanceService
{
    public function __construct(private readonly AvanceRepository $avances) {}

    public function registrar(
        int $proyectoId,
        array $actividadIds,
        int $usuarioId,
        array $input,
        EstadoAvanceStrategy $strategy,
        ?UploadedFile $file
    ): ?string {
        $filename = $file ? $this->guardarArchivo($file, $proyectoId) : '';
        $stateFields = $strategy->fields($input);
        $attributes = array_map(
            fn (int $actividadId): array => [
                'proyecto' => $proyectoId,
                'actividad' => $actividadId,
                'fecha' => now(),
                'fuenteValidacion' => $filename,
                'estado' => $input['est'],
                'observaciones' => $input['obs'] ?? '',
                'justificacion' => $stateFields['justificacion'],
                'usuario' => $usuarioId,
                'inicio' => $stateFields['inicio'],
                'fin' => $stateFields['fin'],
            ],
            $actividadIds
        );

        try {
            DB::transaction(fn () => $this->avances->registrarVarios($attributes));
        } catch (Throwable $exception) {
            $this->eliminarArchivo($proyectoId, $filename);
            throw $exception;
        }

        return $filename !== '' ? $filename : null;
    }

    public function eliminarArchivo(int $proyectoId, string $filename): void
    {
        if ($filename === '') {
            return;
        }

        $safeFilename = basename($filename);
        $paths = [
            public_path("fuenteValidacion/{$proyectoId}/{$safeFilename}"),
            base_path("Sistema/fuenteValidacion/{$proyectoId}/{$safeFilename}"),
        ];

        foreach ($paths as $path) {
            if (File::exists($path) && ! File::delete($path)) {
                throw new \RuntimeException("No se pudo eliminar el archivo de verificación: {$safeFilename}");
            }
        }
    }

    public function rutaArchivo(int $proyectoId, string $filename): ?string
    {
        $safeFilename = basename($filename);
        foreach ([
            public_path("fuenteValidacion/{$proyectoId}/{$safeFilename}"),
            base_path("Sistema/fuenteValidacion/{$proyectoId}/{$safeFilename}"),
        ] as $path) {
            if (File::isFile($path)) {
                return $path;
            }
        }

        return null;
    }

    private function guardarArchivo(UploadedFile $file, int $proyectoId): string
    {
        $originalName = basename($file->getClientOriginalName());
        $extension = strtolower($file->getClientOriginalExtension());
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeBaseName = preg_replace('/[^A-Za-z0-9._-]/', '_', $baseName);
        $safeBaseName = trim(mb_substr($safeBaseName ?: 'archivo', 0, 180), '._-');
        $filename = now()->format('Ymd_His').'_'.bin2hex(random_bytes(3)).'_'.$safeBaseName.'.'.$extension;
        $directory = public_path("fuenteValidacion/{$proyectoId}");

        File::ensureDirectoryExists($directory);
        $file->move($directory, $filename);

        return $filename;
    }
}
