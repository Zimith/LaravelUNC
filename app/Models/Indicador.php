<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicador extends Model
{
    protected $table = 'indicadores';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto');
    }

    public function objetivoEspecifico(): BelongsTo
    {
        return $this->belongsTo(ObjetivoEspecifico::class, 'objetivoEspecifico');
    }
}
