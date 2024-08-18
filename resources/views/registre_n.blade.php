@extends('partials.navbar')

@section('content')
<div class="registrando">
    <h1>Registrando...</h1>
    <div class="regis_dados">
        <h3>Seus dados:</h3>
        <form action="{{ route('regis.nutri') }}" method="post">
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
                        <option selected disabled>Seu sexo</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                <div class="input-group">
                    <input class="btn btn-light" type="submit" value="Finalizar">
                </div>
            </div>
        </form>
    </div>
</div>
@endsection