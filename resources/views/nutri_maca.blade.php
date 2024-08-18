@extends('partials.navbar')

@section('content')
<div class="nutri_maca">
    <div class="nutriai">
        <div class="nutrimaca">
            <img src="{{ asset('images/favicon.ico') }}" id="nutri" alt="Nutri">
            <h3>Olá,eu sou a Nutri Maçã. Pergunte-me algo sobre nutrição! </h3>
        </div>
        <form action="{{ route('nutri_maca') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" name="pergunta">
            <?php if (session('is_nutri') == true) { ?>
            <input type="file" onchange="window.alert('Arquivo anexado.')" name="anamnesia" id="anamnese_junto"
                class="form-control" accept="application/pdf">
            <label for="anamnese_junto">Enviar Anamnese junto</label><br>
            <?php } ?>
            <button type="submit" class="btn btn-light">Enviar Pergunta</button>
        </form>
    </div>
    @if (isset($resposta))
        <div class="resposta">
            <div class="pergunta">
                <h3><strong>Pergunta:</strong><br> {{$pergunta}}</h3>
            </div>
            <h3><strong>Resposta:</strong><br> {{$resposta}}</h3>
        </div>
    @endif
</div>
@endsection