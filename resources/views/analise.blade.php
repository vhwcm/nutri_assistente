@extends('partials.navbar')

@section('content')
<div class="ferramentas">
    <div class="calculo">
        <h2>Análise do Paciente: {{$nome}}</h2>
        <table class="table table-striped">
            <tr>
                <td>
                    Idade:
                </td>
                <td>
                    {{ $idade ?? '' }} anos
                </td>
            </tr>
            <tr>
                <td>
                    Sexo:
                </td>
                <td>
                    {{ $sexo ?? '' }}
                </td>
            </tr>
            <tr>
            <tr>
                <td>
                    Peso:
                </td>
                <td>
                    {{ $peso ?? '' }} kg
                </td>
            </tr>
            <tr>
            <tr>
                <td>
                    IMC(Indice de Massa Corporal):
                </td>
                <td>
                    {{ $imc ?? '' }} kg/m²
                </td>
            </tr>
            <tr>
                <td>
                    TMB(Taxa Metabólica Basal):
                </td>
                <td>
                    {{ $tmb ?? '' }} kcal/dia
                </td>
            </tr>
            <tr>
                <td>
                    GET(Gasto Energético Total):
                </td>
                <td>
                    {{ $get ?? '' }} kcal/dia
                </td>
            </tr>
            <tr>
                <td>
                    Condição:
                </td>
                <td>
                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus"
                        data-bs-content="Disabled popover">
                        <button class="btn btn-primary" id="{{$id}}" type="button" disabled>{{$indice}}</button>
                    </span>
                </td>
            </tr>
            <tr>
        </table>
    </div>

    @endsection