@extends('layouts.dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/read.css') }}">
@endsection
@section('content')
    <div class="container">
        <div class="form-title">Equipos Registrados</div>
        <a href="{{ route('equipos.create')}}" class="btn btn-warning">Nuevo</a>
        <style>
            .team-logo {
                max-width: 100px;
                max-height: 100px;
                width: auto;
                height: auto;
            }
        </style>

        <div class="results-section">
            <table class="table">
                <thead>
                <tr>
                    <th>Nombre del Equipo</th>
                    <th>Patrocinador</th>
                    <th>Monto patrocinador</th>
                    <th>Escudo</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($equipos as $equipo)
                    <tr>
                        <td>{{$equipo->nombre_equipo }}</td>
                        <td>{{$equipo->patrocinador_equipo}}</td>
                        <td>{{$equipo->monto_patrocinador}}</td>
                        <td>
                            <a href="{{ asset('storage/escudos/' . $equipo->escudos) }}" target="_blank">
                            <img src="{{ asset('storage/escudos/' . $equipo->escudos) }}" alt="Escudo del equipo" class="team-logo">
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('equipos.edit', $equipo->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('equipos.destroy', $equipo->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
