@extends('template')
@section('titulo', 'Sucursales')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('contenido')
        <a href="{{route('sucursales.create')}}" >
            <button class="btn btn-success text-white font-bold uppercase">
                crear
            </button>
        </a>


@endsection
@push('js')

@endpush
