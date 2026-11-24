@extends('layouts.app')

@section('title', 'SiMEP - '.$proyecto->titulo)

@section('content')
    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>{{ $proyecto->titulo }}</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <a class="btn btn-primary" style="color: white;" href="{{ route('main') }}">Volver a Proyectos</a>
                        @if ($tipoUsuario !== 6)
                            <a class="btn btn-primary" style="color: white;" href="{{ route('projects.advances.create', $proyecto) }}">Carga de Avances</a>
                        @endif
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.advances.index', $proyecto) }}">Ver Avances cargados</a>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.reports.show', $proyecto) }}">Ver Informes de Avance</a>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1">
                    <div id="request" class="main_form">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="label">Objetivo General</label>
                                <p><strong>{{ $proyecto->objetivoGeneral }}</strong></p>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Objetivos Específicos</label>
                                <div>
                                    @if ($proyecto->objetivosEspecificosLista->isEmpty())
                                        <p>No hay objetivos específicos cargados.</p>
                                    @else
                                        <ul type="square">
                                            @foreach ($proyecto->objetivosEspecificosLista as $objetivo)
                                                <li>{{ $objetivo->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Indicadores</label>
                                <div>
                                    @if ($proyecto->indicadores->isEmpty())
                                        <p>No hay indicadores cargados.</p>
                                    @else
                                        <ul type="square">
                                            @foreach ($proyecto->indicadores as $indicador)
                                                <li>{{ $indicador->nombre }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Actividades</label>
                                <div>
                                    @if ($proyecto->objetivosEspecificosLista->isEmpty())
                                        <p>No hay actividades registradas para este proyecto.</p>
                                    @else
                                        @foreach ($proyecto->objetivosEspecificosLista as $objetivo)
                                            <h4>{{ $objetivo->nombre }}</h4>
                                            <ol>
                                                @foreach ($objetivo->actividades as $actividad)
                                                    @php($estado = $actividad->ultimoAvanceActivo?->estado)
                                                    <li>
                                                        <strong>{{ $actividad->nombre }}</strong> [Tipo: {{ $actividad->tipo }}]<br>
                                                        @if ($estado === 'Finalizada')
                                                            <p style="color: darkgreen;">Estado: {{ $estado }}</p>
                                                        @elseif (empty($estado))
                                                            <p style="color: red;">
                                                                Estado: No informado
                                                                @if ($tipoUsuario !== 6)
                                                                    <a class="send_btn2" href="{{ route('projects.advances.create', ['proyecto' => $proyecto, 'act' => $actividad->id]) }}">Cargar avance</a>
                                                                @endif
                                                            </p>
                                                        @else
                                                            <p style="color: blue;">
                                                                Estado: {{ $estado }}
                                                                @if ($tipoUsuario !== 6)
                                                                    <a class="send_btn2" href="{{ route('projects.advances.create', ['proyecto' => $proyecto, 'act' => $actividad->id]) }}">Cargar avance</a>
                                                                @endif
                                                            </p>
                                                        @endif
                                                        Desde: {{ $actividad->inicioHumano() }}
                                                        Hasta: {{ $actividad->finHumano() }}
                                                    </li>
                                                @endforeach
                                            </ol>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Recursos Humanos</label>
                                <p>{{ $proyecto->recursosHumanos }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Responsable</label>
                                <p>{{ $proyecto->responsable }}</p>
                            </div>
                            <div class="col-md-12">
                                <label class="label">Impacto</label>
                                <p>{{ $proyecto->impacto }}</p><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
