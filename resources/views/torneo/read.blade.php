@extends('layouts.dashboard')
@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/read.css') }}">
@endsection
@section('content')
<div class="container">
    <div class="form-title">Jugadores Registrados</div>
    <div class="results-section">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre Torneo</th>
                    {{-- <th>Tipo Torneo</th> --}}
                    <th>Numero de Equipos</th>
                    <th>Acciones</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($torneo as $torneo)
                <tr>
                    <td>{{ $torneo->nombre_torneo }}</td>
                    {{-- <td>{{ $torneo->tipo_torneo }}</td> --}}
                    <td>{{ $torneo->numero_equipos }}</td>
                    <td>
                        <a href="{{ route('torneo.edit', ['id' => $torneo->id]) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('torneo.destroy', ['id' => $torneo->id]) }}" method="POST" style="display:inline;">
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
