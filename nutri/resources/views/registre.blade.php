@extends('partials.navbar')

@section('content')
<div class="registrando">
    <div class="email">
        <h1>Registrando...</h1>
        <div class="mb-3">
            <form action="{{ route('regis.email') }}" method="post">
                @csrf
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <label for="exampleFormControlInput1" class="form-label">Digite seu email:</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="a">
                                <input type="email" class="form-control" name="email" placeholder="nome@exemplo.com">
                                <button type="submit" class="btn btn-outline-light">Enviar</button>
                        </td>
                    </tr>
            </form>
        </div>
        </table>
    </div>
</div>
</div>
@endsection