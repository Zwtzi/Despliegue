<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partido;
use App\Models\Torneo;
use App\Models\Equipo;
use App\Models\Instalacion;
use App\Models\Jugador;

class PartidosController extends Controller
{
    public function create()
    {
        $torneo = Torneo::all();
        $equiposUsados = Partido::pluck('id_equipo_local')->merge(Partido::pluck('id_equipo_visitante'))->unique();
        $equipos = Equipo::whereNotIn('id', $equiposUsados)->get();
        $instalaciones = Instalacion::all();

        return view('partidos.create', compact('torneo', 'equipos', 'instalaciones'));
    }

    public function finalizar($id)
    {
        $partido = Partido::findOrFail($id);
        $equipos = Equipo::all();
        $jugadoresLocal = Jugador::where('id_equipo', $partido->equipo_local_id)->get();
        $jugadoresVisitante = Jugador::where('id_equipo', $partido->equipo_visitante_id)->get();

        // Obtener el equipo local y visitante
        $equipoLocal = $partido->equipoLocal; // o $equipos->where('id', $partido->equipo_local_id)->first()
        $equipoVisitante = $partido->equipoVisitante; // o $equipos->where('id', $partido->equipo_visitante_id)->first()

        return view('partidos.finalizar', compact('partido', 'jugadoresLocal', 'jugadoresVisitante', 'equipoLocal', 'equipoVisitante'));
    }

    public function procesarFinalizacion(Request $request, $id)
    {
        // Obtener el partido
        $partido = Partido::findOrFail($id);

        // Validación de los datos del formulario
        $validatedData = $request->validate([
            'goles_local' => 'required|integer|min:0',
            'goles_visitante' => 'required|integer|min:0',
            'ganador' => 'required|string|in:local,visitante,empate',
            'faltas_local' => 'required|integer|min:0',
            'faltas_visitante' => 'required|integer|min:0',
            'tarjetas_amarillas_local' => 'required|integer|min:0',
            'tarjetas_amarillas_visitante' => 'required|integer|min:0',
            'jugadores_local' => 'required|array',
            'jugadores_visitante' => 'required|array',
        ]);

        // Asignar los goles y el ganador
        $partido->goles_local = $request->goles_local;
        $partido->goles_visitante = $request->goles_visitante;
        $partido->finalizado = true;

        // Actualizar el equipo local y visitante con los resultados
        $equipoLocal = $partido->equipoLocal;
        $equipoVisitante = $partido->equipoVisitante;

        // Asignar puntos a los equipos
        if ($request->ganador == 'local') {
            $equipoLocal->victorias++;
            $equipoVisitante->derrotas++;
        } elseif ($request->ganador == 'visitante') {
            $equipoVisitante->victorias++;
            $equipoLocal->derrotas++;
        } else {
            $equipoLocal->empates++;
            $equipoVisitante->empates++;
        }

        // Registrar faltas y tarjetas
        $equipoLocal->partidos_jugados++;
        $equipoVisitante->partidos_jugados++;

        // Actualizar estadísticas de jugadores según las faltas
        // Este proceso debe considerar las faltas de los jugadores específicos
        // Luego, se podrían registrar puntos, asistencias, tarjetas, etc.

        // Guardar los cambios
        $equipoLocal->save();
        $equipoVisitante->save();
        $partido->save();

        return redirect()->route('partidos.index')->with('success', 'Partido finalizado correctamente.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_torneo' => 'required|exists:torneos,id',
            'id_equipo_local' => 'required|exists:equipos,id',
            'id_equipo_visitante' => 'required|exists:equipos,id|different:id_equipo_local',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'id_instalacion' => 'required|integer|exists:instalaciones,id',
        ]);

        try {
            // Obtener el torneo y el número de equipos
            $torneo = Torneo::findOrFail($request->id_torneo);
            $numeroEquipos = $torneo->numero_equipos;

            // Calcular el número máximo de partidos iniciales
            $maxPartidos = $numeroEquipos / 2;

            // Contar los partidos ya registrados para este torneo
            $partidosRegistrados = Partido::where('id_torneo', $request->id_torneo)->count();

            if ($partidosRegistrados >= $maxPartidos) {
                return back()
                    ->withErrors([
                        'id_torneo' => 'Ya se han registrado todos los partidos iniciales necesarios para este torneo.',
                    ])
                    ->withInput();
            }

            // Validar conflictos de horario en la instalación
            $fechaHora = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);
            $horaInicio = $fechaHora->copy()->subMinutes(90);
            $horaFin = $fechaHora->copy()->addMinutes(90);

            $conflicto = Partido::where('id_instalacion', $request->id_instalacion)
                ->where('fecha', $request->fecha)
                ->whereBetween('hora', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
                ->exists();

            if ($conflicto) {
                return back()
                    ->withErrors([
                        'hora' => 'Ya existe un partido registrado en esta instalación en un rango de 90 minutos.',
                    ])
                    ->withInput();
            }

            // Crear el partido
            Partido::create([
                'id_torneo' => $request->id_torneo,
                'id_equipo_local' => $request->id_equipo_local,
                'id_equipo_visitante' => $request->id_equipo_visitante,
                'id_instalacion' => $request->id_instalacion,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
            ]);

            return redirect()->route('partidos.create')->with('success', 'Partido registrado exitosamente.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function read()
    {
        $partidos = Partido::all();
        $torneo = Torneo::all();
        $equipos = Equipo::all();
        $instalaciones = Instalacion::all(); // Obtener instalaciones disponibles
        return view('partidos.read', compact('partidos', 'torneo', 'equipos', 'instalaciones'));
    }

    public function edit($id)
    {
        $partido = Partido::findOrFail($id); // Asegúrate de que $partido existe
        $torneos = Torneo::all(); // Obtener todos los torneos
        $equipos = Equipo::all(); // Obtener todos los equipos
        $instalaciones = Instalacion::all(); // Obtener todas las instalaciones

        return view('partidos.edit', compact('partido', 'torneos', 'equipos', 'instalaciones'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id_torneo' => 'required|exists:torneos,id',
            'id_equipo_local' => 'required|exists:equipos,id',
            'id_equipo_visitante' => 'required|exists:equipos,id|different:id_equipo_local',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'id_instalacion' => 'required|exists:instalaciones,id', // Validar que la instalación exista            'hora' => 'required|date_format:H:i',
            'id_instalacion' => 'required|exists:instalaciones,id',
        ]);

        $fechaHora = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);

        $horaInicio = $fechaHora->copy()->subMinutes(90);
        $horaFin = $fechaHora->copy()->addMinutes(90);

        $conflicto = Partido::where('id_instalacion', $request->id_instalacion)
            ->where('fecha', $request->fecha)
            ->whereBetween('hora', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
            ->where('id', '!=', $id)
            ->exists();

        if ($conflicto) {
            return back()
                ->withErrors([
                    'hora' => 'Ya existe un partido registrado en esta instalación en un rango de 90 minutos.',
                ])
                ->withInput();
        }

        $partido = Partido::findOrFail($id);

        $partido->update([
            'id_torneo' => $request->id_torneo,
            'id_equipo_local' => $request->id_equipo_local,
            'id_equipo_visitante' => $request->id_equipo_visitante,
            'id_instalacion' => $request->id_instalacion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
        ]);

        return redirect()->route('partidos.read')->with('success', 'Partido actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();
        return redirect()->route('partidos.read')->with('success', 'Partido eliminado exitosamente.');
    }
}
