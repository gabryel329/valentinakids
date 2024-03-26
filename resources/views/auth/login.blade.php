@extends('layouts.app')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.84.0">

    @php
        $empresa = \App\Models\empresa::first();
    @endphp

    @if($empresa)
        <link rel="shortcut icon" href="{{ asset('images/' . $empresa->imagem) }}" type="image/png">
        <title>Login - {{ $empresa->nome }}</title>
    @endif

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <!-- Custom styles for this template -->
  <link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">

<div class="d-flex justify-content-center align-items-center min-vh-100">
  <main class="form-signin">
    <form class="login-form" method="POST" action="{{ route('login') }}">
      @csrf
      <div class="card p-4">
        @if($empresa)
            <h1 class="h3 mb-3 fw-normal"><img style="width: 40%; aspect-ratio: 3/2; object-fit: contain; margin-bottom: 10px" src="{{ asset('images/' . $empresa->imagem) }}"></h1>
        @endif

        <div class="form-floating mb-3">
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
          <label for="email">E-mail</label>
          @error('email')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="form-floating mb-3">
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
          <label for="password">Senha</label>
          @error('password')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <button style="background-color: #ff0000;" type="submit" class="btn btn-danger">{{ __('Entrar') }}</button> <!-- Alterado para btn-danger -->
      </div>
    </form>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
