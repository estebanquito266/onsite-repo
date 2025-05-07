<!doctype html>
<html lang="es-AR">

<head>
  @include('layouts.baseformpro-head')
</head>

<body>
  <div class="app-container app-theme-white body-tabs-shadow">
    <div class="app-container">
      <div class="h-100 bg-plum-plate bg-animation">
        <div class="d-flex h-100 justify-content-center align-items-center">
          <div class="mx-auto app-login-box col-md-8">
            @include('alerts.errors')
            @include('alerts.request')
            @include('alerts.success')

            <div class="row justify-content-center text-center  mx-auto">
              <div class="modal-body card col-12 col-lg-7 align-center">
                <div class="app-logo-inverse mx-auto mb-1"></div>
              </div>
            </div>


            <div class="modal-dialog w-100 mx-auto">
              <div class="modal-content">
                @yield('content')
              </div>
            </div>
            <div class="text-center text-white opacity-8 mt-1">Copyright © BGH {{ date('Y') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  @section('scripts')
  @show

</body>

</html>