<?php

namespace Tests\Feature;

use App\Services\AvanceService;
use App\Services\EstadosAvance\ContinuaStrategy;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AvanceServiceTest extends TestCase
{
    private string $testPublicPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testPublicPath = storage_path('framework/testing/simep-public-'.bin2hex(random_bytes(6)));
        $this->app->usePublicPath($this->testPublicPath);

        Schema::create('grados_avance', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('proyecto');
            $table->integer('actividad');
            $table->dateTime('fecha');
            $table->string('fuenteValidacion');
            $table->string('estado');
            $table->text('observaciones')->nullable();
            $table->text('justificacion');
            $table->integer('usuario');
            $table->string('inicio');
            $table->string('fin');
            $table->integer('activo')->default(1);
        });
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->testPublicPath);

        parent::tearDown();
    }

    public function test_carga_multiple_guarda_el_archivo_antes_y_persiste_los_campos_not_null(): void
    {
        $filename = app(AvanceService::class)->registrar(
            7,
            [12, 13],
            4,
            ['est' => 'Continua', 'obs' => 'Seguimiento'],
            new ContinuaStrategy,
            UploadedFile::fake()->createWithContent('Evidencia final.pdf', 'contenido de prueba')
        );

        $this->assertNotNull($filename);
        $this->assertMatchesRegularExpression(
            '/^\d{8}_\d{6}_[a-f0-9]{6}_Evidencia_final\.pdf$/',
            $filename
        );
        $this->assertTrue(File::exists($this->testPublicPath.'/fuenteValidacion/7/'.$filename));

        $avances = DB::table('grados_avance')->orderBy('id')->get();
        $this->assertCount(2, $avances);
        foreach ($avances as $avance) {
            $this->assertSame('', $avance->inicio);
            $this->assertSame('', $avance->fin);
            $this->assertSame('', $avance->justificacion);
            $this->assertSame($filename, $avance->fuenteValidacion);
        }

        app(AvanceService::class)->eliminarArchivo(7, $filename);
        $this->assertFalse(File::exists($this->testPublicPath.'/fuenteValidacion/7/'.$filename));
    }
}
