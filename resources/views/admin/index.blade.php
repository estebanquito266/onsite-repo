@extends('layouts.baseprodashboard')

@section('content')


<div class="tabs-animation">
  <div class="row">
    <div class="col-12">
      <div class="card">
        @include('admin.dashboard.accesos_onsite')
      </div>
    </div>
  </div>
</div>

@if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
<div class="tabs-animation mt-3">
  <div class="row">
    <div class="col-12">
      <div class="card">
        @include('admin.dashboard.indicadores')
      </div>
    </div>
  </div>
  </div>
<div class="tabs-animation mt-3">

  <div class="row">
    <div class="col-12">
      <div class="card">
        @include('admin.dashboard.tablas')
      </div>

    </div>
  </div>
</div>
<div class="tabs-animation mt-3">

  <div class="row">
    <div class="col-12">
      <div class="card">
        @include('admin.dashboard.calendar')
      </div>
    </div>
  </div>
</div>


@endif

@endsection

@section('scripts')
<script src="assets/js/dashboard/dashboard.js"></script>
<script src="assets/js/dashboard/indicadores.js"></script>
<script src="assets/js/dashboard/makeAreaGraphics.js"></script>
<script src="assets/js/dashboard/makeTables.js"></script>
<script src="assets/js/dashboard/querys.js"></script>
@endsection