@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/registrarjugadores.css') }}">
@endsection

@section('content')
    <div>
        <div>
            <div class="p-8 bg-gradient-to-r from-blue-200 to-blue-100 rounded-xl shadow-md border border-blue-300">
                <div class="form-title text-2xl font-bold text-gray-800 mb-4">Registrar Jugador</div>

                <form class="player-form" action="{{ route('jugadores.store') }}" method="POST">
                    @csrf
                    <label for="nombres">Nombre completo</label>
                    <input type="text" name="nombres" id="nombres" required>

                    <label for="edad">Edad</label>
                    <input type="number" name="edad" id="edad" min="0" required>

                    <label for="posicion">Posición</label>
                    <input type="text" name="posicion" id="posicion" required>

                    <label for="equipo_id">Equipo</label>
                    <select name="id_equipo" id="equipo_id">
                        <option value="" selected>Selecciona un equipo</option>
                        @foreach(App\Models\Equipo::all() as $equipo)
                            <option value="{{ $equipo->id }}">{{ $equipo->nombre_equipo }}</option>
                        @endforeach
                    </select>

                    {{-- <label for="deporte_id">Deporte</label>
                    <select name="id_deporte" id="deporte_id">
                        <option value="" selected>Selecciona un deporte</option>
                        @foreach(App\Models\Deporte::all() as $deporte)
                            <option value="{{ $deporte->id }}">{{ $deporte->nombre }}</option>
                        @endforeach
                    </select> --}}

                    <button type="submit" class="save-button">Guardar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
