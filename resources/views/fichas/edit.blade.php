@extends('template')

@section('content')
<h2>Editar Ficha Médica de {{ $persona->nombre }}</h2>

<form action="{{ route('fichas.update', $ficha->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label for="detalle_medico_id">Médico</label>
    <select name="detalle_medico_id" id="detalle_medico_id" required>
        @foreach($medicos as $detalle)
            <option value="{{ $detalle->id }}" {{ $detalle->id == $ficha->detalle_medico_id ? 'selected' : '' }}>
                {{ $detalle->medico->nombre }}
            </option>
        @endforeach
    </select>
    @error('detalle_medico_id') <div>{{ $message }}</div> @enderror

    <label for="diagnostico">Diagnóstico</label>
    <textarea name="diagnostico" id="diagnostico" required>{{ old('diagnostico', $ficha->diagnostico) }}</textarea>
    @error('diagnostico') <div>{{ $message }}</div> @enderror

    <label for="consulta_programada">Fecha de Consulta</label>
    <input type="date" name="consulta_programada" id="consulta_programada" 
       value="{{ old('consulta_programada', \Carbon\Carbon::parse($ficha->consulta_programada)->format('Y-m-d')) }}" required>


    <label for="receta_foto">Foto Receta (opcional)</label>
    <input type="file" name="receta_foto" id="receta_foto" accept="image/*">
    @if($ficha->receta_foto)
        <img src="{{ asset('storage/' . $ficha->receta_foto) }}" alt="Receta actual" width="150">
    @endif
    @error('receta_foto') <div>{{ $message }}</div> @enderror

    <button type="submit">Actualizar</button>
    <a href="{{ route('personas.show', $persona->id) }}">Cancelar</a>
</form>
@endsection
