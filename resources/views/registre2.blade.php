@extends('partials.navbar')

@section('content')
<div class="registrando">
    <div class="senha">
        <h1>Registrando...</h1>
        <table class="table table-striped">
            <tr>
                <td>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">{{ $email }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="staticEmail">
                        </div>
                    </div>
                </td>
            </tr>
            <form action="{{route('regis.senha')}}" method="post">
                @csrf
                <tr>
                    <td>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Senha:</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" name="senha" class="form-control"
                                    aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col-auto">
                                <span id="passwordHelpInline" class="form-text">
                                    no minimo 8 caracteres
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="inputPassword6" class="col-form-label">Confirme a senha:</label>
                            </div>
                            <div class="col-auto">
                                <input type="password" name="confirma" class="form-control"
                                    aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col-auto">
                                <span id="passwordHelpInline" class="form-text">
                                    As senhas devem ser identicas
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select class="form-select" name="objetivo" aria-label="Default select example">
                            <option selected disabled>Qual seu objetivo?</option>
                            <option value="n">Sou Nutricionista</option>
                            <option value="p">Sou Paciente/Uso Próprio</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" class="btn btn-outline-light">Enviar</button>
                    </td>
                </tr>
            </form>
    </div>
</div>
</div>
</div>
</div>
@endsection