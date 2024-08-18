@extends('partials.navbar')

@section('content')
<div class="paciente">
    <div class="atualizar_dados">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Atualizar Dados:
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="{{ route('atualizar_dados') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Nome:</span>
                                <input type="text" class="form-control" value="{{$nome}}" name="nome" placeholder="nome"
                                    aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Idade(anos):</span>
                                <input type="number" min="0" max="100" value="{{$idade}}" name="idade"
                                    class="form-control">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Altura(cm):</span>
                                <input type="number" min="100" max="250" value="{{$altura}}" step="0.1" name="altura"
                                    class="form-control">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Peso(kg.g):</span>
                                <input type="number" min="10" max="200" value="{{$peso}}" step="0.1" name="peso"
                                    class="form-control">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Sexo:</span>
                                <select class="form-select" name="sexo" aria-label="Default select example">
                                    <option <?php if ($sexo == 'M') {?> selected <?php } ?> value="M">Masculino</option>
                                    <option <?php if ($sexo == 'F') {?> selected <?php } ?> value="F">Feminino</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Atividade Física:</span>
                                <select class="form-select" name="fa" aria-label="Default select example">
                                    <option <?php if ($fa == 1) {?> selected <?php } ?> value="1">Pouco ou nenhum
                                        exercício, passa a maior parte do dia sentado.
                                    </option>
                                    <option <?php if ($fa == 2) {?> selected <?php } ?> value="2">Exercícios leves de
                                        uma a três vezes por semana.</option>
                                    <option <?php if ($fa == 3) {?> selected <?php } ?> value="3">Exercícios moderados
                                        de três a cinco vezes por semana.
                                    </option>
                                    <option <?php if ($fa == 4) {?> selected <?php } ?> value="4">Exercícios intensos de
                                        seis a sete dias por semana.</option>
                                </select>
                            </div>
                            <div class="input-group" id="button">
                                <input class="btn btn-primary" type="submit" value="Atualizar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection