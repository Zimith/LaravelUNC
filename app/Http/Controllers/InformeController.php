<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\View\View;

class InformeController extends Controller
{
    public function show(Proyecto $proyecto): View
    {
        $informe = $proyecto->informes()->orderByDesc('fecha')->first();

        return view('reports.show', compact('proyecto', 'informe'));
    }
}
