@extends('partials.navbar')

@section('content')
<div class="container">
    <div class="login">
        <h2>Entrar</h2>
        <form action="{{ route('login') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group loginn">
                    <span class="input-group-text">Email</span>
                    <input type="email" name="email" aria-label="Email" class="form-control">
                </div>
                <div class="input-group loginn">
                    <span class="input-group-text">Senha</span>
                    <input type="password" name="senha" aria-label="Senha" class="form-control">
                </div>
                <div class="input-group loginn">
                    <input class="btn btn-light" type="submit" value="Confirmar">
                </div>
            </div>
        </form>
    </div>
    @endsection