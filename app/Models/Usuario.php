<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    public function proyectos(): BelongsToMany
    {
        return $this->belongsToMany(Proyecto::class, 'usuario_x_proyecto', 'usuario', 'proyecto')
            ->using(UsuarioProyecto::class)
            ->withPivot('id');
    }

    public function avances(): HasMany
    {
        return $this->hasMany(GradoAvance::class, 'usuario');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre}, {$this->apellido}";
    }
}
