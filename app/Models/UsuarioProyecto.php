<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioProyecto extends Pivot
{
    protected $table = 'usuario_x_proyecto';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = ['id'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario');
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto');
    }
}
