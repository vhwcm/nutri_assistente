<!DOCTYPE html>
<?php
session_start();
?>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1, width=device-width">

  <!-- http://getbootstrap.com/docs/5.3/ -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <link href="{{asset('css/style.css')}}" rel="stylesheet">

  <title>Nutri Assistente</title>

</head>

<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{route('home')}}">Nutri Assistente</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <?php if (Session::has('session_id')) {?>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('nutri_maca') }}">Nutri Maçã🍎</a>
          </li>
          <?php  if (session('is_nutri') == false) { ?>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('ferramentas_nutri') }}">Ferrramentas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('evolucao') }}">Evolução</a>
          </li>
          <?php  } ?>
          <?php  if (session('is_nutri') == true) { ?>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('ferramentas_nutri') }}">Ferrramentas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('pacientes') }}">Pacientes</a>
          </li>
          <?php  } ?>
        </ul>
        <ul class="navbar-nav ms-auto mt-2">
          <li class="nav-item"><a class="nav-link" href="{{ route('conta') }}">⚙️Minha Conta</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="{{ route('logout')}}">Log Out</a>
          </li>
        </ul>
        <?php } else {?>
        <ul class="navbar-nav ms-auto mt-2">
          <li class="nav-item"><a class="nav-link" href="{{route('regis.email')}}">Registrar-se</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Entrar</a></li>
        </ul>
        <?php }?>
      </div>
    </div>
  </nav>
  <main>
    <?php
if (session('error')) {?>
    <div class="alert alert-secondary">
      {{session('error')}}
    </div>
    <?php }?>
    @yield('content')
  </main>
  <script src="{{ asset('js/app.js') }}"></script>
</body>