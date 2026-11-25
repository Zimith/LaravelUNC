@extends('layouts.app')

@section('title', $puedeEliminar ? 'SiMEP - Gestión de Avances Cargados' : 'SiMEP - Avances Cargados')

@section('content')
    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>{{ $proyecto->titulo }}</h2>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.show', $proyecto) }}">Volver al proyecto</a>
                        @if ((int) session('user_type') !== 6)
                            <a class="btn btn-primary" style="color: white;" href="{{ route('projects.advances.create', $proyecto) }}">Carga de Avances</a>
                        @endif
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.reports.show', $proyecto) }}">Ver Informes de Avance</a>
                    </div>
                </div>

                <div class="col-md-10 offset-md-1">
                    <div id="request" class="main_form">
                        <div class="row">
                            <div class="col-md-12">
                                @if ($puedeEliminar)
                                    <label class="label">Cargas de avances realizadas (Ordenadas por fecha de carga)</label>
                                @else
                                    <label class="label">Cargas de avances realizadas</label>
                                @endif
                                @if ($avances->isEmpty())
                                    <p>Aún no hay avances registrados para este proyecto.</p>
                                @else
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Actividad</th>
                                                <th>Estado</th>
                                                <th>Observaciones</th>
                                                <th>Fuente de Verif.</th>
                                                @if ($puedeEliminar)
                                                    <th>Acción</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($avances as $avance)
                                                <tr>
                                                    <td>{{ substr((string) $avance->getRawOriginal('fecha'), 2, 8) }}</td>
                                                    <td>{{ $avance->actividad?->nombre }}</td>
                                                    <td>{{ $avance->estado }}</td>
                                                    <td>
                                                        {{ $avance->observaciones }}
                                                        @if ($avance->estado === 'No realizada' && !empty($avance->justificacion))
                                                            <br><strong>Justificación:</strong><br>{{ $avance->justificacion }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($avance->fuenteValidacion !== '')
                                                            <a href="{{ route('projects.advances.file', [$proyecto, $avance]) }}" target="_blank" rel="noopener noreferrer">Archivo</a>
                                                        @endif
                                                    </td>
                                                    @if ($puedeEliminar)
                                                        <td>
                                                            <form method="POST" action="{{ route('projects.advances.destroy', [$proyecto, $avance]) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este avance?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link p-0">
                                                                    <img src="{{ asset('images/delete.png') }}" title="Eliminar" width="30px" alt="Eliminar">
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
