@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/registrarpartidos.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Editar Partido</h1>
        <form action="{{ route('partidos.update', $partido->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Método HTTP para actualizar --}}

            <div class="mb-3">
                <label for="id_torneo" class="form-label">Torneo</label>
                <select name="id_torneo" id="id_torneo" class="form-select" required>
                    <option value="">Selecciona un torneo</option>
                    @foreach ($torneos as $torneo)
                        <option value="{{ $torneo->id }}" {{ $torneo->id == $partido->id_torneo ? 'selected' : '' }}>
                            {{ $torneo->nombre_torneo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="id_equipo_local" class="form-label">Equipo Local</label>
                <select name="id_equipo_local" id="id_equipo_local" class="form-select" required>
                    <option value="">Selecciona un equipo</option>
                    @foreach ($equipos as $equipo)
                        <option value="{{ $equipo->id }}" {{ $equipo->id == $partido->id_equipo_local ? 'selected' : '' }}>
                            {{ $equipo->nombre_equipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="id_equipo_visitante" class="form-label">Equipo Visitante</label>
                <select name="id_equipo_visitante" id="id_equipo_visitante" class="form-select" required>
                    <option value="">Selecciona un equipo</option>
                    @foreach ($equipos as $equipo)
                        <option value="{{ $equipo->id }}" {{ $equipo->id == $partido->id_equipo_visitante ? 'selected' : '' }}>
                            {{ $equipo->nombre_equipo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $partido->fecha }}" required>
            </div>

            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="time" name="hora" id="hora" class="form-control" value="{{ $partido->hora }}" required>
            </div>

            <div class="mb-3">
                <label for="id_instalacion" class="form-label">Instalación</label>
                <select name="id_instalacion" id="id_instalacion" class="form-select" required>
                    <option value="">Selecciona una instalación</option>
                    @foreach ($instalaciones as $instalacion)
                        <option value="{{ $instalacion->id }}" {{ $instalacion->id == $partido->id_instalacion ? 'selected' : '' }}>
                            {{ $instalacion->nombre_instalacion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection
