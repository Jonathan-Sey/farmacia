{{-- resources/views/encuesta/respuestas.blade.php --}}
@extends('template')

@section('contenido')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-2">Resultados de la encuesta: {{ $encuesta->titulo }}</h1>
        <p class="text-gray-950 mb-6">Médico evaluado: {{ $encuesta->medico->usuario->name }}</p>

        <div class="space-y-8">
            @foreach($encuesta->preguntas as $pregunta)
            <div class="border-b pb-6">
                <h3 class="text-xl font-semibold mb-4">{{ $pregunta->texto_pregunta }}</h3>

                @if($pregunta->tipo === 'escala')
                <div class="grafico-container mb-4">
                    <h4 class="font-medium mb-2">Promedio: {{ number_format($estadisticas[$pregunta->id]['promedio'], 1) }}/5</h4>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-indigo-600 h-4 rounded-full"
                             style="width: {{ ($estadisticas[$pregunta->id]['promedio']/5)*100 }}%"></div>
                    </div>
                </div>

                {{-- <div class="distribucion grid grid-cols-5 gap-2 text-center">
                    @for($i = 1; $i <= 5; $i++)
                    @php
                        $count = $pregunta->respuestas->where('respuesta', $i)->count();
                        $percentage = $estadisticas[$pregunta->id]['total'] > 0 ? ($count/$estadisticas[$pregunta->id]['total'])*100 : 0;
                    @endphp
                    <div>
                        <div class="text-sm">{{ $i }}</div>
                        <div class="h-32 bg-gray-200 relative">
                            <div class="bg-indigo-400 absolute bottom-0 w-full"
                                 style="height: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-xs mt-1">{{ $count }} ({{ number_format($percentage, 1) }}%)</div>
                    </div>
                    @endfor
                </div> --}}

                @elseif($pregunta->tipo === 'opcion_multiple')
                <div class="opciones-resultado">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opción</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Respuestas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalRespuestas = $pregunta->respuestas->count();
                                $opcionesConteo = [];

                                foreach ($pregunta->opciones as $opcion) {
                                    $opcionesConteo[$opcion] = $pregunta->respuestas->filter(function($respuesta) use ($opcion) {
                                        return $respuesta->respuesta === $opcion;
                                    })->count();
                                }
                            @endphp

                            @foreach($opcionesConteo as $opcion => $conteo)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $opcion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $conteo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $totalRespuestas > 0 ? number_format(($conteo/$totalRespuestas)*100, 1) : 0 }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @elseif ($pregunta->tipo === 'cerrado')
                <div class="distribucion grid grid-cols-5 gap-2 text-center">
                    @for($i = 1; $i <= 2; $i++)
                    @php
                        $count = $pregunta->respuestas->where('respuesta', $i)->count();
                        $percentage = $estadisticas[$pregunta->id]['total'] > 0 ? ($count/$estadisticas[$pregunta->id]['total'])*100 : 0;
                    @endphp
                    <div>
                        <div class="text-sm">{{ $i }}</div>
                        <div class="h-32 bg-gray-200 relative">
                            <div class="bg-indigo-400 absolute bottom-0 w-full"
                                 style="height: {{ $percentage }}%"></div>
                        </div>
                        <div class="text-xs mt-1">{{ $count }} ({{ number_format($percentage, 1) }}%)</div>
                    </div>
                    @endfor
                </div>

                @else
                <div class="respuestas-texto">
                    <h4 class="font-medium mb-2">Respuestas recibidas ({{ $pregunta->respuestas->count() }}):</h4>
                    <div class="space-y-2 max-h-60 overflow-y-auto p-2 bg-gray-50 rounded">
                        @foreach($pregunta->respuestas as $respuesta)
                        <div class="respuesta pb-2 mb-2">
                            <p class="text-gray-700">{{ $respuesta->respuesta }}</p>
                            <p class="text-xs text-gray-500">Respondido el: {{ $respuesta->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            <a href="{{ route('encuestas.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600">
                Volver a las encuestas
            </a>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .distribucion div {
        min-width: 50px;
    }
    .grafico-container {
        max-width: 500px;
    }
</style>
@endpush