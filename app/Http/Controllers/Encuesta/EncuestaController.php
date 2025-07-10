<?php

namespace App\Http\Controllers\Encuesta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\Especialidades;
use App\Models\DetalleMedico;
use App\Models\Encuestas;
use App\Models\Preguntas;
use App\Models\Respuestas;
use App\Models\User;

class EncuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $encuestas = Encuestas::with(['medico.usuario'])->latest()->get();
        return view('encuesta.index', compact('encuestas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medicos = DetalleMedico::with('usuario','especialidad')->get();
        $usuarios = User::all();
        $sucursales = Sucursal::all(); // Asegúrate de que esta consulta está correcta
        $especialidades = Especialidades::all(); // Si necesitas las especialidades, puedes obtenerlas aquí
        return view('encuesta.create', compact('sucursales','especialidades','usuarios','medicos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'detalle_medico_id' => 'required|exists:detalle_medico,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'preguntas' => 'required|array|min:1',
            'preguntas.*.texto' => 'required|string',
            'preguntas.*.tipo' => 'required|in:escala,cerrado,texto',
        ]);

        // Crear encuesta
        $encuesta = Encuestas::create([
            'medico_id' => $request->detalle_medico_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
        ]);

        // Crear preguntas
        foreach ($request->preguntas as $index => $preguntaData) {
            $pregunta = new Preguntas([
                'texto_pregunta' => $preguntaData['texto'],
                'tipo' => $preguntaData['tipo'],
                'orden' => $index,
            ]);

            // if ($preguntaData['tipo'] === 'opcion_multiple' && isset($preguntaData['opciones'])) {
            //     $pregunta->opciones = $preguntaData['opciones'];
            // }

            $encuesta->preguntas()->save($pregunta);
        }

        return redirect()->route('encuestas.index')->with('success', 'Encuesta creada exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     // Mostrar formulario para responder encuesta
    public function showResponder(Encuestas $encuesta)
    {
        $encuesta->load('preguntas');
        //dd($encuesta);
        return view('Encuesta.responder', compact('encuesta'));
    }

    // Procesar respuestas
    public function storeRespuesta(Request $request, Encuestas $encuesta)
    {
        $request->validate([
            'respuestas' => 'required|array',
            'respuestas.*' => 'required',
        ]);
        //dd($request);
        //validamos que se guarde de manera correcta la respuesta con la pregunta
        foreach($request->respuestas as $index => $respuesta){
            Respuestas::create([
                'pregunta_id' => $index,
                'paciente_id' => 1,
                'respuesta' => $respuesta,
            ]);
        }

        return redirect()->route('encuestas.index')->with('success', '¡Gracias por completar la encuesta!');
    }

    // Ver resultados del medico.
    public function verRespuestas(Encuestas $encuesta)
    {
        $encuesta->load(['preguntas.respuestas', 'medico.usuario']);
        //dd($estadisticas); todo el conjuto de datos de la encuesta, preguntas y respuestas
        $estadisticas = $this->calcularEstadisticas($encuesta);
        //dd($estadisticas); array con las datos estadisticos como promedio, max, min, total
        //dd($estadisticas);
        return view('encuesta.respuestas', compact('encuesta', 'estadisticas'));
    }

    protected function calcularEstadisticas($encuesta)
    {
        $estadisticas = [];
        $estadisticasCerradas = [];
        // recorremos todas las preguntas de esta encuesta
        foreach ($encuesta->preguntas as $pregunta) {
            // una vez identificado determinamos que tipo de pregunta es.
            if ($pregunta->tipo === 'escala') {
                // con el uso de pluck buscamos todas esas coincidencias del campo espuesta.
                // con map pasan estos valores a  numeros enteros.
                $respuestas = $pregunta->respuestas->pluck('respuesta')->map(function($item) {
                    return (int)$item;
                });

                $estadisticas[$pregunta->id] = [
                    'promedio' => $respuestas->avg(),
                    'max' => $respuestas->max(),
                    'min' => $respuestas->min(),
                    'total' => $respuestas->count()
                ];
            }

            // para el cerrado utilizaremos la misma logica para obtener los datos
            // en este caso sera para 2 opciones

            if ($pregunta->tipo === 'cerrado') {
                // con el uso de pluck buscamos todas esas coincidencias del campo espuesta.
                // con map pasan estos valores a  numeros enteros.
                $respuestas = $pregunta->respuestas->pluck('respuesta')->map(function($item) {
                    return (int)$item;
                });

                $estadisticas[$pregunta->id] = [
                    'promedio' => $respuestas->avg(),
                    'max' => $respuestas->max(),
                    'min' => $respuestas->min(),
                    'total' => $respuestas->count()
                ];
            }
        }

        return $estadisticas;
    }



    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Encuestas $encuesta)
    {
        $medicos = DetalleMedico::with('usuario','especialidad')->get();
        $encuesta->load('preguntas');
        //dd($encuesta);
        return view('Encuesta.edit', compact('encuesta', 'medicos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Encuestas $encuesta)
    {   
                
        $request->validate([
            'detalle_medico_id' => 'required|exists:detalle_medico,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
             'preguntas' => 'required|array|min:1',
             'preguntas.*.texto' => 'required|string',
             'preguntas.*.tipo' => 'required|in:escala,cerrado,texto',            
        ]);
        dd($request);
        
        $encuesta->update([
                'medico_id' => $request->detalle_medico_id,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
        ]);


        

        return redirect()->route('encuestas.index')->with('success', 'La encuesta fue actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
