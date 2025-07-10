@extends('template')

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <h1 class="text-2xl font-bold mb-4">{{ $encuesta->titulo }}</h1>
        <p class="mb-6">{{ $encuesta->descripcion }}</p>

        <form action="{{ route('encuestas.storeRespuesta', $encuesta) }}" method="POST">
            @csrf

            <div class="space-y-6">
                @foreach($encuesta->preguntas as $pregunta)
                <div class="pregunta border-b pb-4">
                    <label class="block text-lg font-medium mb-2">
                        {{ $loop->iteration }}. {{ $pregunta->texto_pregunta }}
                    </label>

                    @if($pregunta->tipo === 'escala')
                    <div class=" m-auto w-[400px] flex justify-between">
                            @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <span class="mb-1">{{$i}}</span>
                                <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{$i}}">
                            </label>
                            @endfor
                        {{-- @for($i = 1; $i <= 5; $i++)
                        <label class="flex flex-col items-center">
                            <span class="mb-1">{{ $i }}</span>
                            <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $i }}" required>
                        </label>
                        @endfor --}}
                    </div>
                    <div class="flex justify-between text-xs mt-1">
                        <span>Muy insatisfecho</span>
                        <span>Muy satisfecho</span>
                    </div>

                    @elseif($pregunta->tipo === 'cerrado')
                    <div class=" w-[400px] flex justify-start gap-5">
                        @for ($i = 1; $i <= 2; $i++)
                        <label class="flex flex-col items-center">
                            @if ($i === 1)
                                <span class="mb-1">Si</span>
                            @else
                                <span class="mb-1">No</span>
                            @endif
                            <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{$i}}">
                        </label>
                        @endfor
                    </div>
                    {{-- <div class="opciones space-y-2">
                        @foreach($pregunta->opciones as $opcion)
                        <label class="flex items-center">
                            <input type="radio" name="respuestas[{{ $pregunta->id }}]" value="{{ $opcion }}" class="mr-2" required>
                            {{ $opcion }}
                        </label>
                        @endforeach
                    </div> --}}
                    @else
                    <textarea name="respuestas[{{ $pregunta->id }}]" class="w-full rounded-md border-gray-300 shadow-sm" rows="3" required></textarea>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                    Enviar Encuesta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('css')
<style>
    .pregunta {
        padding: 1rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
    }
</style>
@endpush