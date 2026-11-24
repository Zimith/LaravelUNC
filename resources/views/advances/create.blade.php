@extends('layouts.app')

@section('title', 'SiMEP - Carga de Avances')

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
                        <h3><br><strong>Carga de avance por Actividades</strong></h3><br>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.show', $proyecto) }}">Volver al proyecto</a>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.advances.create-multiple', $proyecto) }}">Cargar varias actividades juntas</a>
                        <a class="btn btn-primary" style="color: white;" href="{{ route('projects.reports.show', $proyecto) }}">Ver Informes de Avance</a>
                        <a class="btn btn-primary" style="color: white;" href="https://drive.google.com/file/d/1sZOy_44toI55ZY4FU8RDuZnIWN2PxwmD/view?usp=sharing" target="_blank" rel="noopener noreferrer">Informe 2do Taller</a>
                    </div>
                </div>

                <div class="col-md-10 offset-md-1">
                    <form id="request" class="main_form" action="{{ route('projects.advances.store', $proyecto) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{ $proyecto->id }}" name="idProy" id="idProy">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="label" for="actividad">Seleccione una actividad (*)</label>
                                <select class="form-control" name="actividad" id="actividad" required>
                                    <option value="">-- Seleccione una actividad --</option>
                                    @foreach ($actividades as $actividad)
                                        <option value="{{ $actividad->id }}" @selected((string) $actividadPreseleccionada === (string) $actividad->id)>{{ $actividad->nombre }}</option>
                                    @endforeach
                                </select>

                                <div id="extension2" name="extension2"></div>

                                <label class="label" for="est">Estado de la Actividad (*)</label>
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
            const scheduleTemplate = @json(route('projects.activities.schedule', [$proyecto, 'ACTIVITY_ID']));

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

            async function cargarExtensionActividad(idActividad) {
                const extension = $("#extension2");
                extension.html("");
                if (!idActividad) {
                    return;
                }

                try {
                    const response = await fetch(scheduleTemplate.replace("ACTIVITY_ID", idActividad), {
                        headers: { "Accept": "application/json" }
                    });
                    if (!response.ok) {
                        return;
                    }
                    const dates = await response.json();
                    extension.html('<div class="form-group"><label for="cronograma_inicio">Fecha de inicio según cronograma</label><input type="text" class="form-control" id="cronograma_inicio" name="cronograma_inicio" readonly><label for="cronograma_fin">Fecha de finalización según cronograma</label><input type="text" class="form-control" id="cronograma_fin" name="cronograma_fin" readonly></div>');
                    document.getElementById("cronograma_inicio").value = dates.inicio;
                    document.getElementById("cronograma_fin").value = dates.fin;
                } catch {
                    extension.html("");
                }
            }

            $("#est").on("change", function() {
                cargarExtensionEstado($(this).val());
            });

            $("#actividad").on("change", function() {
                cargarExtensionActividad($(this).val());
            });

            if ($("#actividad").val()) {
                cargarExtensionActividad($("#actividad").val());
            }
        });
    </script>
@endpush
