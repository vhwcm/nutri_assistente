@extends('partials.navbar')

@section('content')
<div class="paciente">
    <div class="escolher_paciente">
        <h2>Escolher um paciente para analisar:</h2>
        <form action="{{ route('analisar_paciente') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class=" input-group">
                <select class="form-select" name="paciente" aria-label="Default select example">
                    <option selected>Selecione um paciente</option>
                    <?php foreach ($pacientes as $paciente) { ?>
                    <option value="{{$paciente->id}}">{{$paciente->nome}}</option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" name="action" value="atualizar_dados">Atualizar Dados</button>
            <button type="submit" name="action" value="baixar_anamnese">Baixar Anamnese</button>
            <input type="file" onchange="this.form.submit()" name="action" id="enviar_anamnese" class="form-control"
                accept="application/pdf">
            <label for="enviar_anamnese">Enviar Anamnese</label>
            <button type="submit" name="action" value="evolucao">Ver Evolução</button>
            <button type="submit" name="action" value="deletar">Deletar</button>
        </form>
    </div>
    <div class="novo_paciente">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Adicionar um paciente
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="{{ route('pacientes') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Nome:</span>
                                <input type="text" class="form-control" name="nome" placeholder="nome"
                                    aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Idade(anos):</span>
                                <input type="number" min="0" max="100" name="idade" class="form-control">
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
                                    <option value="1">Pouco ou nenhum exercício, passa a maior parte do dia sentado.
                                    </option>
                                    <option value="2">Exercícios leves de uma a três vezes por semana.</option>
                                    <option value="3">Exercícios moderados de três a cinco vezes por semana.
                                    </option>
                                    <option value="4">Exercícios intensos de seis a sete dias por semana.</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Anaminesia(pdf):</span>
                                <input type="file" name="anaminesia" class="form-control" accept="application/pdf">
                                </select>
                            </div>
                            <div class="input-group" id="button">
                                <input class="btn btn-secondary" type="submit" value="Adicionar">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection