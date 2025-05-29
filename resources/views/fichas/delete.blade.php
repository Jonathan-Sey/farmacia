@extends('template')

@section('content')
<h2>Eliminar Ficha Médica</h2>

<p>¿Está seguro que desea eliminar esta ficha médica del paciente {{ $ficha->persona->nombre }}?</p>

<ul>
    <li>Diagnóstico: {{ $ficha->diagnostico }}</li>
    <li>Consulta programada: {{ $ficha->consulta_programada->format('d/m/Y') }}</li>
    @if($ficha->receta_foto)
    <li><img src="{{ asset('storage/' . $ficha->receta_foto) }}" alt="Receta" width="150"></li>
    @endif
</ul>

<form action="{{ route('fichas.destroy', $ficha->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" onclick="return confirm('¿Confirma eliminar esta ficha?')">Eliminar</button>
    <a href="{{ route('personas.show', $ficha->persona_id) }}">Cancelar</a>
</form>
@endsection
