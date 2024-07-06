@extends('partials.navbar')

@section('content')

<div class="registrando">
    <h1>Registrando...</h1>
    <div class="regis_dados">
        <h3>Informações adicionais:</h3>
        <form action="{{ route('regis.dados') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group">
                    <span class="input-group-text">Primeiro e último nome</span>
                    <input type="text" name="primeiro" aria-label="First name" class="form-control">
                    <input type="text" name="ultimo" aria-label="Last name" class="form-control">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Qual é seu sexo?</span>
                    <select class="form-select" name="sexo" aria-label="Default select example">
                        <option disabled selected>Selecione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                <div class="input-group">
                    <span class="input-group-text">Qual é sua idade(anos)?</span>
                    <input type="number" min="0" max="120" name="idade" placeholder="anos" class="form-control">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Qual é sua altura(cm)?</span>
                    <input type="number" min="100" step="0.1" max="250" name="altura" placeholder="Centímetros"
                        class="form-control">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Qual é seu peso(kg.g)?</span>
                    <input type="number" min="10" max="200" step="0.1" name="peso" placeholder="Quilos"
                        class="form-control">
                </div>
                <div class="input-group">
                    <span class="input-group-text">Qual seu nivel de atividade física?</span>
                    <select class="form-select" name="atividade_fisica" aria-label="Default select example">
                        <option disabled selected>Escolha</option>
                        <option value="1">Pouco ou nenhum exercício, passa a maior parte do dia sentado.</option>
                        <option value="2">Exercícios leves de uma a três vezes por semana.</option>
                        <option value="3">Exercícios moderados de três a cinco vezes por semana.</option>
                        <option value="4">Exercícios intensos de seis a sete dias por semana.</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Código da Nutri(caso tenha):</span>
                    <input type="text" name="codigo" class="form-control" aria-label="Sizing example input"
                        aria-describedby="inputGroup-sizing-default">
                </div>

                <div class="input-group">
                    <input class="btn btn-light" type="submit" value="Finalizar">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection