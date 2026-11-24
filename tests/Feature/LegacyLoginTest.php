<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyLoginTest extends TestCase
{
    public function test_login_acepta_la_contrasena_legacy_en_texto_plano(): void
    {
        Schema::dropIfExists('usuarios');
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
        DB::table('usuarios')->insert([
            'username' => 'responsable',
            'password' => 'clave-legacy',
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'tipo' => 1,
        ]);

        $response = $this->post(route('login.store'), [
            'user' => 'responsable',
            'pass' => 'clave-legacy',
        ]);

        $response->assertRedirect(route('main'));
        $this->assertSame(1, session('user_id'));
        $this->assertSame(1, session('user_type'));
    }

    public function test_login_rechaza_una_contrasena_incorrecta(): void
    {
        Schema::dropIfExists('usuarios');
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
        DB::table('usuarios')->insert([
            'username' => 'responsable',
            'password' => 'clave-legacy',
            'nombre' => 'Ana',
            'apellido' => 'Pérez',
            'tipo' => 1,
        ]);

        $response = $this->from(route('login'))->post(route('login.store'), [
            'user' => 'responsable',
            'pass' => 'incorrecta',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('user');
        $this->assertFalse(session()->has('user_id'));
    }
}
