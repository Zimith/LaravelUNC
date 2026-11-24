<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradoAvance extends Model
{
    protected $table = 'grados_avance';

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'fecha' => 'datetime',
        'activo' => 'integer',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto');
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class, 'actividad');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario');
    }
}
