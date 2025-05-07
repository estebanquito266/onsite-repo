@extends('layouts.baseformpro')

@section('content')

<form method="POST" action="{{ route('login') }}">
  @csrf

  <div class="modal-body">
    <div class="h5 modal-title text-center">
      <h4 class="mt-2">
        <div>¡Bienvenido a SpeedUP Onsite!</div>
        <span>Por favor, loguearse para acceder a su cuenta.</span>
      </h4>
    </div>

      <div class="col-md-12">
        <div class="form-row">
          <div class="col-md-12">
            <div class="position-relative form-group">
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Ingrese su email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

              @error('email')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          <div class="col-md-12">
            <div class="position-relative form-group">
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingrese su password" name="password" required autocomplete="current-password">

              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
        </div>

        <div class="position-relative form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

          <label class="form-check-label" for="remember">Mantenerme logueado</label>
        </div>

        <div class="divider"></div>
        <h6 class="mb-0">&nbsp;</h6>

      </div>

      <div class="modal-footer clearfix">
        <div class="float-left">
          @if (Route::has('password.request'))
            <a class="btn-lg btn btn-link" href="{{ route('password.request') }}">
                {{ __('¿Olvidó su password?') }}
            </a>
          @endif
        </div>
        <div class="float-right">
          <button class="btn btn-lg btn-primary btn-block">Iniciar</button>
        </div>
      </div>
  </div>
</form>
  
@endsection