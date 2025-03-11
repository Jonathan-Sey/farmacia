@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ficha Médica de {{ $ficha->paciente->nombre }}</h2>
    <ul>
        <li><strong>Médico:</strong> {{ $ficha->medico->nombre }}</li>
        <li><strong>Edad:</strong> {{ $ficha->edad }}</li>
        <li><strong>Peso:</strong> {{ $ficha->peso }} kg</li>
        <li><strong>Altura:</strong> {{ $ficha->altura }} m</li>
        <li><strong>Presión Arterial:</strong> {{ $ficha->presion_arterial }}</li>
        <li><strong>Síntomas:</strong> {{ $ficha->sintomas }}</li>
        <li><strong>Diagnóstico:</strong> {{ $ficha->diagnostico }}</li>
        <li><strong>Tratamiento:</strong> {{ $ficha->tratamiento }}</li>
    </ul>
</div>
@endsection
