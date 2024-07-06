@extends('partials.navbar')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="evolucao">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOn"
                    aria-expanded="true" aria-controls="collapseOne">
                    Evolução: {{$nome}}
                </button>
            </h2>
            <div id="collapseOn" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div>
                        <canvas id="evolucao"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Atualizar Dados:
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form action="{{ route('atualizar_dados') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Nome:</span>
                            <input type="text" class="form-control" value="{{$paciente->nome}}" name="nome"
                                placeholder="nome" aria-label="Username" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Idade(anos):</span>
                            <input type="number" min="0" max="100" value="{{$paciente->idade}}" name="idade"
                                class="form-control">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Altura(cm):</span>
                            <input type="number" min="100" max="250" value="{{$paciente->altura}}" step="0.1"
                                name="altura" class="form-control">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Peso(kg.g):</span>
                            <input type="number" min="10" max="200" value="{{$paciente->peso}}" step="0.1" name="peso"
                                class="form-control">
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Sexo:</span>
                            <select class="form-select" name="sexo" aria-label="Default select example">
                                <option <?php if ($paciente->sexo == true) {?> selected <?php } ?> value="M">
                                    Masculino
                                </option>
                                <option <?php if ($paciente->sexo == false) {?> selected <?php } ?> value="F">
                                    Feminino
                                </option>
                            </select>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text">Atividade Física:</span>
                            <select class="form-select" name="fa" aria-label="Default select example">
                                <option <?php if ($paciente->fa == 1) {?> selected <?php } ?> value="1">
                                    Pouco ou
                                    nenhum
                                    exercício, passa a maior parte do dia sentado.
                                </option>
                                <option <?php if ($paciente->fa == 2) {?> selected <?php } ?> value="2">
                                    Exercícios
                                    leves de
                                    uma a três vezes por semana.</option>
                                <option <?php if ($paciente->fa == 3) {?> selected <?php } ?> value="3">
                                    Exercícios
                                    moderados
                                    de três a cinco vezes por semana.
                                </option>
                                <option <?php if ($paciente->fa == 4) {?> selected <?php } ?> value="4">
                                    Exercícios
                                    intensos de
                                    seis a sete dias por semana.</option>
                            </select>
                        </div>
                        <div class="input-group" id="button">
                            <input class="btn btn-light" type="submit" value="Atualizar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('evolucao');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [@foreach ($datas as $data)
                "{{$data}}",
            @endforeach],
            datasets: [{
                label: 'Peso(kg)',
                data: [@foreach ($pesos as $peso)
                    {{$peso}},
                @endforeach],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection