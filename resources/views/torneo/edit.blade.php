@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/editarjugador2.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>Editar Torneo</h1>
        <form class="player-form" action="{{ route('torneo.update', $torneo->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nombre_torneo" class="form-label">Nombre Torneo</label>
                <input type="text" name="nombre_torneo" id="nombre_torneo" class="form-control"
                    value="{{ $torneo->nombre_torneo }}" required>
            </div>

            {{-- <div class="mb-3">
                <label for="tipo_torneo" class="form-label">Tipo</label>
                <input type="text" name="tipo_torneo" id="tipo_torneo" class="form-control"
                    value="{{ $torneo->tipo_torneo }}" required>
            </div> --}}

            <div class="mb-3">
                <label for="numero_equipos" class="form-label">Numero de Equipos</label>
                <input type="number" name="numero_equipos" id="numero_equipos" class="form-control"
                    value="{{ $torneo->numero_equipos }}" required>
            </div>


            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection
