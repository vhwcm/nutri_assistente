@extends('partials.navbar')

@section('content')
<div class="home">
  <div class="home-part" id="u">
    <h2> Bem vindo, Essa é a Nutri Assistente!</h>
      <h3> Com o objetivo de ajudar nutricionistas e pacientes🍎💪</h3>
  </div>
  <div class="home-part" id="d">
    <h2>Funcionalidades da Nutri Assistente</h2>
    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Ferramentas⚙️
          </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            Calcule rapidamente o IMC do seu paciente, a quantidade de calorias que ele deve consumir por dia e muito
            mais.
            É possivel calcular avulso ou fazer o cálculo de um paciente em sua lista com base nos dados.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
            aria-expanded="true" aria-controls="collapseOne">
            Nutri Maçã🍎
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            Feita para retirar dúvidas de forma rápida sobre nutrição, a Nutri Maçã é uma assistente virtual que pode
            auxiliar em dicas e alimentos adequados para cada pessoa, mas lembrando que ela não substitui uma
            nutricionista e pode cometer erros. Atualmente ela está desativada devido ao servidor ser de graça, mas pode ficar ativa no futuro.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          pacientes💪
        </button>
      </h2>
      <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Adicione pacientes à sua lista, veja o histórico de consultas, crie planos alimentares e muito mais.
          Verifique e atualize os dados de seus pacientes. Todo paciente recebe por padrão uma anamnesia limpa sempre
          que é adicionado.
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="home-part" id="p">
    <div class="nutriai">
      <div class="nutrimaca">
        <img src="{{ asset('images/favicon.ico') }}" id="nutri" alt="Nutri">
        <h3>Olá,eu sou a Nutri Maçã. Pergunte-me algo sobre nutrição! </h3>
      </div>
      <form action="{{ route('nutri_maca') }}" method="post">
        @csrf
        <input type="text" name="pergunta">
        <button type="submit">Enviar</button>
      </form>
    </div>
  </div>
</div>
@endsection