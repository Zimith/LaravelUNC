<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Actividad extends Model
{
    protected $table = 'actividades';

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

    public function avances(): HasMany
    {
        return $this->hasMany(GradoAvance::class, 'actividad');
    }

    public function ultimoAvanceActivo(): HasOne
    {
        return $this->hasOne(GradoAvance::class, 'actividad')
            ->where('activo', 1)
            ->latestOfMany('id');
    }

    public function inicioHumano(bool $conDelAnio = false): string
    {
        return $this->formatearTrimestre($this->inicio, $conDelAnio);
    }

    public function finHumano(bool $conDelAnio = false): string
    {
        return $this->formatearTrimestre($this->fin, $conDelAnio);
    }

    public function inicioHumanoCronograma(): string
    {
        return $this->formatearTrimestre($this->inicio2);
    }

    public function finHumanoCronograma(): string
    {
        return $this->formatearTrimestre($this->fin2);
    }

    private function formatearTrimestre(mixed $value, bool $conDelAnio = false): string
    {
        $number = (int) $value;
        if ($number <= 0) {
            return 'Sin definir';
        }

        $quarter = intdiv($number, 100);
        $year = $number % 100;
        $yearText = $conDelAnio ? 'del año 20' : 'de 20';

        return "{$quarter}º trimestre {$yearText}{$year}";
    }
}
