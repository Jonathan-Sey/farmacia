@extends('template')
@section('titulo','Categorias')

@push('css')

@endpush

@section('contenido')
    <h1>Editar Categorias</h1>

    <ul>
        @foreach ($categorias as $categoria )
        <li>{{$categoria->nombre}}</li>
        <li>{{$categoria->created_at}}</li>
        @if ($categoria->estado == 1)
            <li>Activo</li>
        @else
            <li>Inactivo</li>
        @endif
        <li>
            Creado el:
            @if ($categoria->created_at)
            {{ $categoria->created_at->format('d-m-Y') }}
            @else
            No disponible
            @endif
        </li>
        @endforeach
    </ul>

@endsection
@push('js')

@endpush
