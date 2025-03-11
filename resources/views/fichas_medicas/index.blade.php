@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Fichas Médicas</h2>
    <a href="{{ route('fichas_medicas.create') }}" class="btn btn-primary">Nueva Ficha Médica</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Edad</th>
                <th>Peso</th>
                <th>Altura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fichas as $ficha)
            <tr>
                <td>{{ $ficha->paciente->nombre }}</td>
                <td>{{ $ficha->medico->nombre }}</td>
                <td>{{ $ficha->edad }}</td>
                <td>{{ $ficha->peso }} kg</td>
                <td>{{ $ficha->altura }} m</td>
                <td>
                    <a href="{{ route('fichas_medicas.show', $ficha) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('fichas_medicas.edit', $ficha) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('fichas_medicas.destroy', $ficha) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
