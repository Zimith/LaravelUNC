<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Repositories\ProyectoRepository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProjectRepositoryTest extends TestCase
{
    public function test_repository_lista_solo_los_proyectos_asignados_al_usuario(): void
    {
        Schema::create('usuarios', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->nullable();
            $table->integer('tipo');
            $table->text('descripcion')->nullable();
        });
        Schema::create('proyectos', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('titulo');
        });
        Schema::create('usuario_x_proyecto', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('usuario');
            $table->integer('proyecto');
        });
        DB::table('usuarios')->insert([
            'username' => 'responsable',
            'password' => 'legacy',
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'tipo' => 1,
        ]);
        DB::table('proyectos')->insert([
            ['titulo' => 'Asignado'],
            ['titulo' => 'No asignado'],
        ]);
        DB::table('usuario_x_proyecto')->insert([
            'usuario' => 1,
            'proyecto' => 1,
        ]);

        $usuario = Usuario::query()->findOrFail(1);
        $proyectos = app(ProyectoRepository::class)->asignadosA($usuario);

        $this->assertSame(['Asignado'], $proyectos->pluck('titulo')->all());
        $this->assertSame(1, $usuario->proyectos->first()->pivot->id);
    }
}
