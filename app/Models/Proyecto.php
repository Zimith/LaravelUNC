<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(Usuario::class, 'usuario_x_proyecto', 'proyecto', 'usuario')
            ->using(UsuarioProyecto::class)
            ->withPivot('id');
    }

    public function objetivosEspecificosLista(): HasMany
    {
        return $this->hasMany(ObjetivoEspecifico::class, 'proyecto');
    }

    public function indicadores(): HasMany
    {
        return $this->hasMany(Indicador::class, 'proyecto');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'proyecto');
    }

    public function avances(): HasMany
    {
        return $this->hasMany(GradoAvance::class, 'proyecto');
    }

    public function informes(): HasMany
    {
        return $this->hasMany(InformeAvance::class, 'proyecto');
    }
}
