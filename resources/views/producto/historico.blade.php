@extends('template')
@section('titulo', 'Historico de Precios')
@section('contenido')
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Anterior</th>
                <th>Precio Nuevo</th>
                <th>Fecha de Cambio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($historico as $registro)
                <tr>
                    <td>{{ $registro->producto->nombre }}</td>
                    <td>{{ number_format($registro->precio_anterior, 2) }}</td>
                    <td>{{ number_format($registro->precio_nuevo, 2) }}</td>
                    <td>{{ $registro->fecha_cambio }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
