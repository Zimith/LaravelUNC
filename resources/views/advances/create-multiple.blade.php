@extends('layouts.app')

@section('title', 'SiMEP - Carga Múltiple de Avances')

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
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                        @endif
                        <h3><br><strong>Utilizar solo cuando una fuente de validación corresponde para varias actividades</strong></h3><br>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.show', $proyecto) }}">Volver al proyecto</a>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.advances.create', $proyecto) }}">Cargar una actividad individual</a>
                    </div>
                </div>

                <div class="col-md-10 offset-md-1">
                    <form id="request" class="main_form" action="{{ route('projects.advances.store-multiple', $proyecto) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $proyecto->id }}" name="idProy" id="idProy">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="label">Seleccione las actividades a informar (*)</label>
                                <div class="form-group" style="max-height: 260px; overflow-y: auto; border: 1px solid #ced4da; padding: 12px; border-radius: 4px; background: #fff;">
                                    @if ($actividades->isEmpty())
                                        <p>No hay actividades disponibles para este proyecto.</p>
                                    @else
                                        @foreach ($actividades as $actividad)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="act[]" id="act_{{ $actividad->id }}" value="{{ $actividad->id }}">
                                                <label class="form-check-label" for="act_{{ $actividad->id }}">{{ $actividad->nombre }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <label class="label" for="est">Estado de las Actividades (*)</label>
                                <select class="form-control" name="est" id="est" required>
                                    <option value="">-- Seleccione un estado --</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado }}">{{ $estado }}</option>
                                    @endforeach
                                </select>

                                <label class="label" for="obs">Observaciones (opcional)</label>
                                <textarea class="login" placeholder="Puede agregar más información sobre el estado de la Actividad" name="obs" id="obs"></textarea>

                                <div id="extension" name="extension"></div>

                                <label class="label" name="labelFV" id="labelFV" for="fuenteV">Fuente de verificación (Archivo jpg, pdf, zip o rar) (*)</label>
                                <input type="file" class="form-control" id="fuenteV" name="fuenteV" required>
                            </div>

                            <div class="col-md-12">
                                <label class="label label-danger">(*) Datos requeridos</label>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="send_btn">Informar</button><br><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function cargarExtensionEstado(elegido) {
                const extension = $("#extension");
                const fuente = document.getElementById("fuenteV");
                const label = document.getElementById("labelFV");
                extension.html("");
                fuente.required = elegido !== "No realizada";
                label.textContent = fuente.required
                    ? "Fuente de verificación (Archivo jpg, pdf, zip o rar) (*)"
                    : "Fuente de verificación (Archivo jpg, pdf, zip o rar)";

                if (elegido === "Finalizada") {
                    extension.html('<div class="form-group"><label for="inicio">Fecha de inicio</label><input type="date" class="form-control" id="inicio" name="inicio"><label for="fin">Fecha de finalización</label><input type="date" class="form-control" id="fin" name="fin"></div>');
                } else if (elegido === "En ejecución") {
                    extension.html('<div class="form-group"><label for="inicio">Fecha de inicio</label><input type="date" class="form-control" id="inicio" name="inicio"></div>');
                } else if (elegido === "No realizada") {
                    extension.html('<div class="form-group"><label class="label" for="just">Justificación (*)</label><textarea class="login" placeholder="Justificación de por qué no fue realizada la actividad" name="just" id="just" required></textarea></div>');
                }
            }

            $("#est").on("change", function() {
                cargarExtensionEstado($(this).val());
            });
        });
    </script>
@endpush
