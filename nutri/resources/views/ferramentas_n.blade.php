@extends('partials.navbar')

@section('content')
<div class="ferramentas">
    <form action="{{ route('ferramentas_nutri') }}" method="post">
        @csrf
        <div class="avulso">
            <h4>Avulso:</h4>
            <div class="input-group">
                <span class="input-group-text">Idade(anos):</span>
                <input type="number" min="0" max="100" name="idade" class="form-control">
            </div>
            <div class="input-group" id="metodoa" style="display:none">
                <select class="form-select" name="metodoa" aria-label="Default select example">
                    <option selected disabled>Metodo TMB infantil</option>
                    <option value="F">FAO/OMS</option>
                    <option value="D">DRI/IOM</option>
                    <option value="S">Scholfield</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-text">Altura(cm):</span>
                <input type="number" min="100" max="250" step="0.1" name="altura" class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-text">Peso(kg.g):</span>
                <input type="number" min="10" max="200" step="0.1" name="peso" class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-text">Sexo:</span>
                <select class="form-select" name="sexo" aria-label="Default select example">
                    <option selected disabled>Selecione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>
            </div>
            <div class="input-group">
                <span class="input-group-text">Atividade Física:</span>
                <select class="form-select" name="atividade_fisica" aria-label="Default select example">
                    <option selected disabled>Selecione</option>
                    <option value="1">Pouco ou nenhum exercício, passa a maior parte do dia sentado.</option>
                    <option value="2">Exercícios leves de uma a três vezes por semana.</option>
                    <option value="3">Exercícios moderados de três a cinco vezes por semana.</option>
                    <option value="4">Exercícios intensos de seis a sete dias por semana.</option>
                </select>
            </div>
            <div class="input-group" id="button">
                <input class="btn btn-light" type="submit" value="calcular">
            </div>
    </form>
</div>
<h4>OU</h4>
@if (session('is_nutri') == true)
    <div class="escolher_paciente">
        <form action="{{ route('analise') }}" method="post">
            @csrf
            <h4>Escolher um paciente para analisar:</h4>
            <div class="input-group">
                <select class="form-select" name="paciente" aria-label="Default select example">
                    <option selected disabled>Selecione um paciente</option>
                    <?php    foreach ($pacientes as $paciente) { ?>
                    <option value="{{$paciente->id}}" data-idade="{{$paciente->idade}}">{{$paciente->nome}}</option>
                    <?php    } ?>
                </select>
            </div>
            <div class="input-group" id="metodo" style="display:none">
                <select class="form-select" name="metodo" aria-label="Default select example">
                    <option selected disabled>Método para TMB</option>
                    <option value="F">FAO/OMS</option>
                    <option value="D">DRI/IOM</option>
                    <option value="S">Scholfield</option>
                </select>
            </div>
            <div id="button" class="input-group">
                <input class="btn btn-dark" type="submit" value="calcular">
            </div>
        </form>
    </div>
@else
    <div class="escolher_paciente">
        <h4>Com base nos seus dados pessoais</h4>
        <table class="table table-striped">
            <tr>
                <td>
                    Peso:
                </td>
                <td>
                    {{ $paciente->peso ?? '' }} kg
                </td>
            </tr>
            <tr>
                <td>
                    Altura:
                </td>
                <td>
                    {{ $paciente->altura ?? '' }} cm
                </td>
            </tr>
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
                        <button class="btn btn-dark" id="{{$id}}" type="button" disabled>{{$indice}}</button>
                    </span>
                </td>
            </tr>
        </table>
    </div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectPaciente = document.querySelector('select[name="paciente"]');
        selectPaciente.addEventListener('change', function () {
            const opcaoSelecionada = this.options[this.selectedIndex];
            const idade = parseInt(opcaoSelecionada.getAttribute('data-idade'), 10);
            if (idade < 18) {
                document.getElementById('metodo').style.display = '';
            } else {
                document.getElementById('metodo').style.display = 'none';
            }
        });

    });
    document.addEventListener('DOMContentLoaded', function () {
        const inputIdade = document.querySelector('input[name="idade"]');
        inputIdade.addEventListener('input', function () {
            const idade = parseInt(this.value, 10);
            if (!isNaN(idade) && idade < 18) {
                document.getElementById('metodoa').style.display = '';
            } else {
                document.getElementById('metodoa').style.display = 'none';
            }
        });
    });
</script>
@endsection