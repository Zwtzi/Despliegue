<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torneo;

class TorneoController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre_torneo' => 'required|string|max:255|unique:torneos,nombre_torneo',
            'patrocinador_torneo' => 'nullable|string|max:255',
            'monto_patrocinador' => 'nullable|integer',
            'numero_equipos' => 'required|integer|min:2', // Opcional: asegura mínimo 2 equipos.
        ]);

        Torneo::create([
            'nombre_torneo' => $validatedData['nombre_torneo'],
            'patrocinador_torneo' => $validatedData['patrocinador_torneo'] ?? 'Sin patrocinador',
            'monto_patrocinador' => $validatedData['monto_patrocinador'] ?? 0,
            'numero_equipos' => $validatedData['numero_equipos'],
        ]);

        return redirect()->route('torneo.create')->with('success', 'Torneo registrado exitosamente.');
    }

    public function edit($id)
    {
        $torneo = Torneo::findOrFail($id);
        return view('torneo.edit', compact('torneo'));
    }

    public function update(Request $request, $id)
    {
        $torneo = Torneo::findOrFail($id);

       $request->validate([
            'nombre_torneo' => 'required|string|max:255|unique:torneos,nombre_torneo,' . $torneo->id,
            'patrocinador_torneo' => 'nullable|string|max:255',
            'monto_patrocinador' => 'nullable|integer',
            'numero_equipos' => 'required|integer|min:2',
        ]);

        $torneo->update([
            'nombre_torneo' => $request->nombre_torneo,
            'patrocinador_torneo' => $request->patrocinador_torneo ?? 'Sin patrocinador',
            'monto_patrocinador' => $request->monto_patrocinador ?? 0,
            'numero_equipos' => $request->numero_equipos,
        ]);

        return redirect()->route('torneo.read')->with('success', 'Torneo actualizado con éxito.');
    }

    public function destroy($id)
    {
        $torneo = Torneo::findOrFail($id);
        $torneo->delete();

        return redirect()->route('torneo.read')->with('success', 'Torneo eliminado con éxito.');
    }

    public function create()
    {
        return view('torneo.create');
    }

    public function read()
    {
        $torneos = Torneo::all();
        return view('torneo.read', compact('torneos'));
    }
}
