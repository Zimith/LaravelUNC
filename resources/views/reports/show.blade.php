@extends('layouts.app')

@section('title', 'SiMEP - Informes de Avance')

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
                                <label class="label">Informe de avances de UEPEP</label>
                                <div>
                                    @if (! $informe || $informe->informe === '')
                                        <p>No hay informes de avance registrados para este proyecto.</p>
                                    @else
                                        {{ $informe->informe }}
                                    @endif
                                </div>
                                <p><br></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
