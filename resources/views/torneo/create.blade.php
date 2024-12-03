@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('Css/creartorneo.css') }}">
@endsection

@section('content')
    <div>
        <div>
            <div class="p-8 bg-gradient-to-r from-blue-200 to-blue-100 rounded-xl shadow-md border border-blue-300">
                <div class="form-title text-2xl font-bold text-gray-800 mb-4">Registrar Torneo</div>

                <form class="player-form" action="{{ route('torneo.store') }}" method="POST">
                    @csrf
                    <label>Nombre Torneo</label>
                    <input type="text" name="nombre_torneo" required>
                    <label for="patrocinador_torneo">Patrocinador del Torneo</label>
                    <input type="text" id="patrocinador_torneo" name="patrocinador_torneo">
                    <br class="jump">

                    <label for="monto_patrocinador">Monto patrocinador</label>
                    <input type="number" id="monto_patrocinador" name="monto_patrocinador">
                    <br class="jump">


                    {{-- <label>Tipo de Torneo</label>
                    <input type="text" name="tipo_torneo" required> --}}

                    <label for="numero_equipos" class="font-semibold text-gray-700">Número de Equipos</label>
                    <select name="numero_equipos" id="numero_equipos"
                        class="w-full p-3 mt-2 mb-4 border border-gray-300 rounded-lg" required>
                        <option value="" selected disabled>Selecciona el número de equipos</option>
                        <option value="4">4</option>
                        <option value="8">8</option>
                        <option value="16">16</option>
                    </select>


                    <button type="submit"
                        class="w-full bg-green-600 text-white p-3 mt-4 rounded-lg hover:bg-green-700 transition duration-300">Guardar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
