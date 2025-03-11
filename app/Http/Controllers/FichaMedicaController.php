<?php

namespace App\Http\Controllers;

use App\Models\FichaMedica;
use App\Models\Persona;
use App\Models\DetalleMedico;
use Illuminate\Http\Request;

class FichaMedicaController extends Controller {
    public function index() {
        $fichas = FichaMedica::with(['paciente', 'medico'])->get();
        return view('fichas_medicas.index', compact('fichas'));
    }

    public function create() {
        $pacientes = Persona::all();
        $medicos = DetalleMedico::all();
        return view('fichas_medicas.create', compact('pacientes', 'medicos'));
    }

    public function store(Request $request) {
        $request->validate([
            'id_persona' => 'required|exists:persona,id',
            'id_medico' => 'required|exists:detalle_medico,id',
            'edad' => 'required|integer',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'presion_arterial' => 'nullable|string',
            'sintomas' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string'
        ]);

        FichaMedica::create($request->all());

        return redirect()->route('fichas_medicas.index')->with('success', 'Ficha médica registrada.');
    }

    public function show(FichaMedica $ficha) {
        return view('fichas_medicas.show', compact('ficha'));
    }

    public function edit(FichaMedica $ficha) {
        $pacientes = Persona::all();
        $medicos = DetalleMedico::all();
        return view('fichas_medicas.edit', compact('ficha', 'pacientes', 'medicos'));
    }

    public function update(Request $request, FichaMedica $ficha) {
        $request->validate([
            'edad' => 'required|integer',
            'peso' => 'required|numeric',
            'altura' => 'required|numeric',
            'presion_arterial' => 'nullable|string',
            'sintomas' => 'nullable|string',
            'diagnostico' => 'nullable|string',
            'tratamiento' => 'nullable|string'
        ]);

        $ficha->update($request->all());

        return redirect()->route('fichas_medicas.index')->with('success', 'Ficha médica actualizada.');
    }

    public function destroy(FichaMedica $ficha) {
        $ficha->delete();
        return redirect()->route('fichas_medicas.index')->with('success', 'Ficha médica eliminada.');
    }
}

