@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/registrarequipo.css') }}">
@endsection

@section('content')

<div class="container">
    <h1>Editar Jugador</h1>
    <br>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form class="player-form" action="{{ route('jugadores.update', $jugador->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombres">Nombre(s)</label>
            <input type="text" name="nombres" class="form-control" value="{{ $jugador->nombres }}" required>
        </div>

        <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" class="form-control" value="{{ $jugador->edad }}" min="0" required>
        </div>

        <div class="form-group">
            <label for="posicion">Posición</label>
            <input type="text" name="posicion" class="form-control" value="{{ $jugador->posicion }}" required>
        </div>

        <div class="form-group">
            <label for="id_equipo">Equipo</label>
            <select name="id_equipo" class="form-control">
                <option value="">Selecciona un equipo</option>
                @foreach(App\Models\Equipo::all() as $equipo)
                    <option value="{{ $equipo->id }}" {{ $jugador->id_equipo == $equipo->id ? 'selected' : '' }}>
                        {{ $equipo->nombre_equipo }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- <div class="form-group">
            <label for="id_deporte">Deporte</label>
            <select name="id_deporte" class="form-control">
                <option value="">Selecciona un deporte</option>
                @foreach(App\Models\Deporte::all() as $deporte)
                    <option value="{{ $deporte->id }}" {{ $jugador->id_deporte == $deporte->id ? 'selected' : '' }}>
                        {{ $deporte->nombre }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <button type="submit" class="actualizar btn btn-primary">Actualizar</button>
        <a href="{{ route('jugador.read') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

@endsection
