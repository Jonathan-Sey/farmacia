@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Ficha Médica</h2>
    <form action="{{ route('fichas_medicas.update', $ficha) }}" method="POST">
        @csrf @method('PUT')
        @include('fichas_medicas._form')
        <button type="submit" class="btn btn-warning">Actualizar Ficha</button>
    </form>
</div>
@endsection
