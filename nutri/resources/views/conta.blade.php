@extends('partials.navbar')

@section('content')
<div class="title">
    <h1>Conta</h1>
</div>
<div class="conta">
    <br>
    <h2>Nome: {{ $user->name }}</h2>
    <h2>Email: {{ $user->email }}</h2>
    @if (session('is_nutri') == true)
        <h2>Código: {{ $codigo}}</h2>
    @endif
    <div class="form-group">
        <form action="deletar_conta" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">Deletar Conta</button>
        </form>
        <br>
        <form action="logout" method="get">
            @csrf
            <button type="submit" class="btn btn-danger">Sair</button>
        </form>
    </div>
</div>
@endsection