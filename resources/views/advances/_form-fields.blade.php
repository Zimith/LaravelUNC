<label class="label" for="est">Estado de la Actividad (*)</label>
<select class="form-control" name="est" id="est" required>
    <option value="">-- Seleccione un estado --</option>
    @foreach ($estados as $estado)
        <option value="{{ $estado }}" @selected(old('est') === $estado)>{{ $estado }}</option>
    @endforeach
</select>

<label class="label" for="obs">Observaciones (opcional)</label>
<textarea class="login" name="obs" id="obs" placeholder="Puede agregar más información sobre el estado de la Actividad">{{ old('obs') }}</textarea>

<div id="estado-fields"></div>
@foreach ($errors->all() as $error)
    <div class="alert alert-danger mt-2">{{ $error }}</div>
@endforeach

<label class="label" for="fuenteV" id="fuenteV-label">Fuente de verificación (*)</label>
<input type="file" class="form-control" id="fuenteV" name="fuenteV" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
<small>Formatos permitidos: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, zip y rar.</small>
