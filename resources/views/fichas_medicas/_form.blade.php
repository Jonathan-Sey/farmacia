<div class="mb-3">
    <label for="id_persona" class="form-label">Paciente</label>
    <select name="id_persona" class="form-control" required>
        @foreach ($pacientes as $paciente)
            <option value="{{ $paciente->id }}" {{ isset($ficha) && $ficha->id_persona == $paciente->id ? 'selected' : '' }}>
                {{ $paciente->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="id_medico" class="form-label">Médico</label>
    <select name="id_medico" class="form-control" required>
        @foreach ($medicos as $medico)
            <option value="{{ $medico->id }}" {{ isset($ficha) && $ficha->id_medico == $medico->id ? 'selected' : '' }}>
                {{ $medico->nombre }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="edad" class="form-label">Edad</label>
    <input type="number" name="edad" class="form-control" value="{{ $ficha->edad ?? '' }}" required>
</div>

<div class="mb-3">
    <label for="peso" class="form-label">Peso (kg)</label>
    <input type="number" step="0.1" name="peso" class="form-control" value="{{ $ficha->peso ?? '' }}" required>
</div>

<div class="mb-3">
    <label for="altura" class="form-label">Altura (m)</label>
    <input type="number" step="0.01" name="altura" class="form-control" value="{{ $ficha->altura ?? '' }}" required>
</div>

<div class="mb-3">
    <label for="presion_arterial" class="form-label">Presión Arterial</label>
    <input type="text" name="presion_arterial" class="form-control" value="{{ $ficha->presion_arterial ?? '' }}">
</div>

<div class="mb-3">
    <label for="sintomas" class="form-label">Síntomas</label>
    <textarea name="sintomas" class="form-control">{{ $ficha->sintomas ?? '' }}</textarea>
</div>

<div class="mb-3">
    <label for="diagnostico" class="form-label">Diagnóstico</label>
    <textarea name="diagnostico" class="form-control">{{ $ficha->diagnostico ?? '' }}</textarea>
</div>
s
<div class="mb-3">
    <label for="tratamiento" class="form-label">Tratamiento</label>
    <textarea name="tratamiento" class="form-control">{{ $ficha->tratamiento ?? '' }}</textarea>
</div>
