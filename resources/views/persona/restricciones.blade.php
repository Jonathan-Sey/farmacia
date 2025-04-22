@extends('template')

@section('contenido')
<div class="card">
    <div class="card-header">
        <h3>Control de Compras para {{ $persona->nombre }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('personas.actualizar-restricciones', $persona) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Límite de compras</label>
                <input type="number" name="limite_compras" value="{{ $persona->limite_compras }}" class="form-control">
                <small>Número máximo de compras permitidas en el período</small>
            </div>

            <div class="form-group">
                <label>Período de control (días)</label>
                <input type="number" name="periodo_control" value="{{ $persona->periodo_control }}" class="form-control">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="restriccion_activa" id="restriccion_activa"
                       class="form-check-input" {{ $persona->restriccion_activa ? 'checked' : '' }}>
                <label class="form-check-label" for="restriccion_activa">Restricción activa</label>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

        <hr>

        <h4 class="mt-4">Alertas Recientes</h4>
        @forelse($alertas as $alerta)
        <div class="alert alert-{{ $alerta->leida ? 'secondary' : 'warning' }}">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>{{ $alerta->tipo }}</strong>
                    <p>{{ $alerta->descripcion }}</p>
                    <small>{{ $alerta->created_at->format('d/m/Y H:i') }}</small>
                </div>
                @if(!$alerta->leida)
                <form action="{{ route('alertas.marcar-leida', $alerta) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Marcar como leída</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="alert alert-info">No hay alertas registradas</div>
        @endforelse
    </div>
</div>
@endsection