<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdvancePagesTest extends TestCase
{
    public function test_formularios_de_avance_se_muestran_y_el_tipo_seis_no_puede_cargarlos(): void
    {
        Schema::dropIfExists('actividades');
        Schema::dropIfExists('proyectos');

        Schema::create('proyectos', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('titulo');
            $table->text('objetivosEspecificos')->nullable();
        });
        Schema::create('actividades', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('nombre');
            $table->string('tipo');
            $table->string('inicio')->nullable();
            $table->string('fin');
            $table->string('inicio2')->nullable();
            $table->string('fin2')->nullable();
            $table->integer('proyecto');
            $table->integer('objetivoEspecifico');
        });
        DB::table('proyectos')->insert([
            'titulo' => 'Proyecto de prueba',
            'objetivosEspecificos' => 'Objetivos heredados del proyecto 1',
        ]);
        DB::table('proyectos')->insert([
            'id' => 2,
            'titulo' => 'Proyecto de prueba 2',
            'objetivosEspecificos' => 'Objetivos heredados del proyecto 2',
        ]);
        DB::table('actividades')->insert([
            'nombre' => 'Actividad de prueba',
            'tipo' => 'Continua',
            'inicio' => '323',
            'fin' => '423',
            'inicio2' => '324',
            'fin2' => '424',
            'proyecto' => 1,
            'objetivoEspecifico' => 1,
        ]);
        DB::table('actividades')->insert([
            'nombre' => 'Actividad del proyecto 2',
            'tipo' => 'Continua',
            'inicio' => '323',
            'fin' => '423',
            'inicio2' => '324',
            'fin2' => '424',
            'proyecto' => 2,
            'objetivoEspecifico' => 2,
        ]);

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->get(route('projects.advances.create', 1))
            ->assertOk()
            ->assertSee('Proyecto de prueba')
            ->assertSee('ACTIVITY_ID');

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->get(route('projects.advances.create-multiple', 1))
            ->assertOk()
            ->assertSee('name="act[]"', false);

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->get(route('projects.activities.schedule', [1, 1]))
            ->assertOk()
            ->assertExactJson([
                'inicio' => '3º trimestre de 2024',
                'fin' => '4º trimestre de 2024',
            ]);

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->from(route('projects.advances.create', 1))
            ->post(route('projects.advances.store', 1), [
                'actividad' => 1,
                'est' => 'Continua',
            ])
            ->assertSessionHasErrors('fuenteV');

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->from(route('projects.advances.create', 1))
            ->post(route('projects.advances.store', 1), [
                'actividad' => 1,
                'est' => 'Continua',
                'fuenteV' => UploadedFile::fake()->createWithContent('script.exe', 'no permitido'),
            ])
            ->assertSessionHasErrors('fuenteV');

        Schema::create('objetivos_especificos', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('proyecto');
        });
        Schema::create('indicadores', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('proyecto');
            $table->integer('objetivoEspecifico');
        });
        Schema::create('grados_avance', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('actividad');
            $table->string('estado');
            $table->integer('activo');
        });
        DB::table('objetivos_especificos')->insert(['nombre' => 'Objetivo de prueba', 'proyecto' => 1]);
        DB::table('objetivos_especificos')->insert(['nombre' => 'Objetivo del proyecto 2', 'proyecto' => 2]);
        DB::table('indicadores')->insert([
            'nombre' => 'Indicador de prueba',
            'proyecto' => 1,
            'objetivoEspecifico' => 1,
        ]);
        DB::table('indicadores')->insert([
            'nombre' => 'Indicador del proyecto 2',
            'proyecto' => 2,
            'objetivoEspecifico' => 2,
        ]);
        DB::table('grados_avance')->insert([
            ['actividad' => 1, 'estado' => 'Finalizada', 'activo' => 1],
            ['actividad' => 1, 'estado' => 'En ejecución', 'activo' => 1],
            ['actividad' => 2, 'estado' => 'Continua', 'activo' => 1],
        ]);

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->get(route('projects.show', 1))
            ->assertOk()
            ->assertSee('Objetivo de prueba')
            ->assertSee('Indicador de prueba')
            ->assertSee('Actividad de prueba')
            ->assertSee('Estado: En ejecución')
            ->assertDontSee('Estado: Finalizada');

        $this->withSession(['user_id' => 1, 'user_type' => 1])
            ->get('/projects/2')
            ->assertOk()
            ->assertSee('Proyecto de prueba 2')
            ->assertSee('Objetivo del proyecto 2')
            ->assertSee('Indicador del proyecto 2')
            ->assertSee('Actividad del proyecto 2')
            ->assertSee('Estado: Continua');

        $this->withSession(['user_id' => 1, 'user_type' => 6])
            ->get(route('projects.advances.create', 1))
            ->assertForbidden();
    }
}
