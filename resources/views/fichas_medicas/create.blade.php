@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Nueva Ficha Médica</h2>
    <form action="{{ route('fichas_medicas.store') }}" method="POST">
        @csrf
        @include('fichas_medicas._form')
        <button type="submit" class="btn btn-success">Guardar Ficha</button>
    </form>
</div>
@endsection
