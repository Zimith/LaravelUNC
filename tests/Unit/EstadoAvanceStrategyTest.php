<?php

namespace Tests\Unit;

use App\Services\EstadosAvance\ContinuaStrategy;
use App\Services\EstadosAvance\EnEjecucionStrategy;
use App\Services\EstadosAvance\FinalizadaStrategy;
use App\Services\EstadosAvance\NoRealizadaStrategy;
use PHPUnit\Framework\TestCase;

class EstadoAvanceStrategyTest extends TestCase
{
    public function test_estado_continua_vacia_los_campos_no_utilizados_y_exige_archivo(): void
    {
        $strategy = new ContinuaStrategy;

        $this->assertSame([
            'inicio' => '',
            'fin' => '',
            'justificacion' => '',
        ], $strategy->fields([]));
        $this->assertTrue($strategy->requiresFile());
    }

    public function test_estado_no_realizada_exige_justificacion_y_permite_omitir_archivo(): void
    {
        $strategy = new NoRealizadaStrategy;

        $this->assertSame(['just' => ['required', 'string']], $strategy->rules());
        $this->assertSame([
            'inicio' => '',
            'fin' => '',
            'justificacion' => 'Motivo',
        ], $strategy->fields(['just' => 'Motivo']));
        $this->assertFalse($strategy->requiresFile());
    }

    public function test_estados_con_fechas_conservan_las_informadas_y_limpian_las_ausentes(): void
    {
        $running = new EnEjecucionStrategy;
        $finished = new FinalizadaStrategy;

        $this->assertSame([
            'inicio' => '2026-03-01',
            'fin' => '',
            'justificacion' => '',
        ], $running->fields(['inicio' => '2026-03-01']));
        $this->assertSame([
            'inicio' => '2026-02-01',
            'fin' => '2026-03-01',
            'justificacion' => '',
        ], $finished->fields(['inicio' => '2026-02-01', 'fin' => '2026-03-01']));
    }
}
