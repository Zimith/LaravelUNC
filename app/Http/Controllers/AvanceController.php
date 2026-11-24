<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAvanceRequest;
use App\Http\Requests\StoreAvancesMultiplesRequest;
use App\Models\GradoAvance;
use App\Models\Proyecto;
use App\Repositories\AvanceRepository;
use App\Repositories\ProyectoRepository;
use App\Services\AvanceService;
use App\Services\EstadosAvance\EstadoAvanceStrategyFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AvanceController extends Controller
{
    public function __construct(
        private readonly AvanceRepository $avances,
        private readonly ProyectoRepository $proyectos,
        private readonly AvanceService $avanceService,
        private readonly EstadoAvanceStrategyFactory $strategies
    ) {}

    public function create(Proyecto $proyecto): View
    {
        return view('advances.create', [
            'proyecto' => $proyecto,
            'actividades' => $this->proyectos->actividades($proyecto->id),
            'actividadPreseleccionada' => request()->query('act'),
            'estados' => $this->strategies->states(),
        ]);
    }

    public function createMultiple(Proyecto $proyecto): View
    {
        return view('advances.create-multiple', [
            'proyecto' => $proyecto,
            'actividades' => $this->proyectos->actividades($proyecto->id),
            'estados' => $this->strategies->states(),
        ]);
    }

    public function store(StoreAvanceRequest $request, Proyecto $proyecto): RedirectResponse
    {
        $input = $request->validated();
        $strategy = $this->strategies->for($input['est']);
        $this->avanceService->registrar(
            $proyecto->id,
            [(int) $input['actividad']],
            (int) session('user_id'),
            $input,
            $strategy,
            $request->file('fuenteV')
        );

        return redirect()
            ->route('projects.advances.create', $proyecto)
            ->with('status', 'Avance registrado con éxito.');
    }

    public function storeMultiple(StoreAvancesMultiplesRequest $request, Proyecto $proyecto): RedirectResponse
    {
        $input = $request->validated();
        $strategy = $this->strategies->for($input['est']);
        $this->avanceService->registrar(
            $proyecto->id,
            array_map('intval', $input['act']),
            (int) session('user_id'),
            $input,
            $strategy,
            $request->file('fuenteV')
        );

        return redirect()
            ->route('projects.advances.create-multiple', $proyecto)
            ->with('status', 'Avances registrados con éxito.');
    }

    public function index(Proyecto $proyecto): View
    {
        return view('advances.index', [
            'proyecto' => $proyecto,
            'avances' => $this->avances->porProyecto($proyecto->id),
            'puedeEliminar' => (int) session('user_type') === 6,
        ]);
    }

    public function destroy(Proyecto $proyecto, GradoAvance $avance): RedirectResponse
    {
        abort_unless((int) $avance->proyecto === (int) $proyecto->id, Response::HTTP_NOT_FOUND);

        $avance = $this->avances->eliminarLogicamente($proyecto->id, $avance->id);
        abort_if($avance === null, Response::HTTP_NOT_FOUND);
        $this->avanceService->eliminarArchivo($proyecto->id, (string) $avance->fuenteValidacion);

        return redirect()
            ->route('projects.advances.index', $proyecto)
            ->with('status', 'Avance eliminado con éxito.');
    }

    public function schedule(Proyecto $proyecto, int $actividad): array
    {
        $actividad = $proyecto->actividades()->findOrFail($actividad);

        return [
            'inicio' => $actividad->inicioHumanoCronograma(),
            'fin' => $actividad->finHumanoCronograma(),
        ];
    }

    public function download(Proyecto $proyecto, GradoAvance $avance): BinaryFileResponse
    {
        abort_unless((int) $avance->proyecto === (int) $proyecto->id, Response::HTTP_NOT_FOUND);
        abort_unless($avance->activo === 1, Response::HTTP_NOT_FOUND);
        $path = $this->avanceService->rutaArchivo($proyecto->id, (string) $avance->fuenteValidacion);
        abort_unless($path !== null, Response::HTTP_NOT_FOUND);

        return response()->download($path, basename($path));
    }
}
