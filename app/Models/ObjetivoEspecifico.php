<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjetivoEspecifico extends Model
{
    protected $table = 'objetivos_especificos';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'objetivoEspecifico');
    }

    public function indicadores(): HasMany
    {
        return $this->hasMany(Indicador::class, 'objetivoEspecifico');
    }
}
