@extends('layouts.dashboard')

@section('content')
    <div class="form-title">Finalizar Partido</div>
    <form action="{{ route('partidos.procesarFinalizacion', $partido->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="goles_local">Goles del Equipo Local:</label>
            <input type="number" name="goles_local" id="goles_local" class="form-control" value="{{ old('goles_local') }}" required>
        </div>

        <div class="form-group">
            <label for="goles_visitante">Goles del Equipo Visitante:</label>
            <input type="number" name="goles_visitante" id="goles_visitante" class="form-control" value="{{ old('goles_visitante') }}" required>
        </div>

        <div class="form-group">
            <label for="ganador">¿Quién ganó?</label>
            <select name="ganador" id="ganador" class="form-control">
                <option value="local">{{ $equipoLocal->nombre_equipo }}</option>
                <option value="visitante">{{ $equipoVisitante->nombre_equipo }}</option>
            </select>
        </div>

        <div class="form-group">
            <label for="faltas_local">Faltas del Equipo Local:</label>
            <input type="number" name="faltas_local" id="faltas_local" class="form-control" value="2">
        </div>

        <div class="form-group">
            <label for="faltas_visitante">Faltas del Equipo Visitante:</label>
            <input type="number" name="faltas_visitante" id="faltas_visitante" class="form-control" value="1">
        </div>

        <!-- Estos divs se llenarán dinámicamente según los goles -->
        <div id="goles_local_inputs"></div>
        <div id="goles_visitante_inputs"></div>

        <button type="submit" class="btn btn-success">Finalizar Partido</button>
    </form>

    <script>
        // Crear un objeto de jugadores de cada equipo en formato JavaScript
        const jugadoresLocal = {!! json_encode($jugadoresLocal->toArray()) !!};
        const jugadoresVisitante = {!! json_encode($jugadoresVisitante->toArray()) !!};

        // Función para crear los selects de los jugadores
        function generarSelectJugadores(goles, jugadores, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = ''; // Limpiar campos anteriores

            for (let i = 0; i < goles; i++) {
                let select = document.createElement('select');
                select.name = containerId === 'goles_local_inputs' ? 'jugadores_local_gol[]' : 'jugadores_visitante_gol[]';
                select.classList.add('form-control');

                jugadores.forEach(jugador => {
                    let option = document.createElement('option');
                    option.value = jugador.id;
                    option.textContent = jugador.nombre;
                    select.appendChild(option);
                });

                let div = document.createElement('div');
                div.classList.add('form-group');
                div.appendChild(select);
                container.appendChild(div);
            }
        }

        // Eventos para los inputs de goles
        document.getElementById('goles_local').addEventListener('input', function() {
            let golesLocal = this.value;
            generarSelectJugadores(golesLocal, jugadoresLocal, 'goles_local_inputs');
        });

        document.getElementById('goles_visitante').addEventListener('input', function() {
            let golesVisitante = this.value;
            generarSelectJugadores(golesVisitante, jugadoresVisitante, 'goles_visitante_inputs');
        });
    </script>
@endsection
