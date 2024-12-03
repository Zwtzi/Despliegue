<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Jugador;
use App\Models\Equipo;
use App\Models\Deporte;

class JugadorController extends Controller
{
    public function dashboard()
    {
        return view("jugador.dashboard");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombres' => 'required|string|max:255|unique:jugadores,nombres',
            'edad' => 'required|integer|min:0',
            'posicion' => 'required|string|max:255',
            'id_equipo' => 'nullable|exists:equipos,id',
        ]);

        Jugador::create([
            'nombres' => $request->nombres,
            'edad' => $request->edad,
            'posicion' => $request->posicion,
            'id_equipo' => $request->id_equipo,
        ]);

        return redirect()->route('jugador.create')->with('success', 'Jugador registrado exitosamente');
    }

    public function edit($id)
    {
        $jugador = Jugador::findOrFail($id);
        $equipos = Equipo::all();
        $deportes = Deporte::all();

        return view('jugador.edit', compact('jugador', 'equipos', 'deportes'));
    }

    public function update(Request $request, $id)
    {
        $jugador = Jugador::findOrFail($id);

        $validatedData = $request->validate([
            'nombres' => 'required|string|max:255|unique:jugadores,nombres,' . $jugador->id,
            'edad' => 'required|integer|min:0',
            'posicion' => 'required|string|max:255',
            'id_equipo' => 'nullable|exists:equipos,id',
        ]);

        $jugador->update([
            'nombres' => $request->nombres,
            'edad' => $request->edad,
            'posicion' => $request->posicion,
            'id_equipo' => $request->id_equipo,
        ]);

        return redirect()->route('jugador.read')->with('success', 'Jugador actualizado con éxito');
    }

    public function destroy($id)
    {
        $jugador = Jugador::findOrFail($id);
        $jugador->delete();

        return redirect()->route('jugador.read')->with('success', 'Jugador eliminado con éxito');
    }

    public function create()
    {
        $equipos = Equipo::all();
        $deportes = Deporte::all();

        return view('jugador.create', compact('equipos'));
    }

    public function read()
    {
        $jugadores = Jugador::all();

        return view('jugador.read', compact('jugadores'));
    }

    public function desempeño()
    {
        $jugadores = Jugador::all();

        return view('jugador.desempeño', compact('jugadores'));
    }

    public function buscar(Request $request)
    {
        $nombre = $request->input('nombres');
        $jugadores = Jugador::where('nombres', 'LIKE', "%$nombre%")->get();

        return response()->json($jugadores);
    }

    public function generarPDFJugadores(Request $request)
    {
        $jugadores = Jugador::where('nombres', 'LIKE', '%' . $request->input('nombres') . '%')->get();
        $pdf = PDF::loadView('pdf.jugadores', compact('jugadores'));

        return $pdf->download('jugadores.pdf');
    }
}
