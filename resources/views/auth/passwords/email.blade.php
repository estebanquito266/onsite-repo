@extends('layouts.baseformpro')

@section('content')

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<div class="modal-header">
  <div class="h5 modal-title">¿Olvidó su Password?<h6 class="mt-1 mb-0 opacity-8"><span>Ingrese su email para obtener un nuevo password.</span></h6></div>
</div>

<form method="POST" action="{{ route('password.email', app()->getLocale()) }}">
  @csrf
  <div class="modal-body">                                    
    <div>                                        
      <div class="form-row">
        <div class="col-md-12">
          <div class="position-relative form-group"><label for="email" class="">Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

              @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
        </div>
      </div>                                        
    </div>

    <div class="divider"></div>
    <h6 class="mb-0"><a href="/" title="loguearse con su cuenta" class="text-primary">Loguearse con su cuenta</a></h6>									
  </div>
  <div class="modal-footer clearfix">
    <div class="float-right">
      <button type="submit" class="btn btn-primary btn-lg">{{ __('Recuperar password') }}</button>
    </div>
  </div>
</form>
@endsection