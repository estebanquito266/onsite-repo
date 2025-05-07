@extends('layouts.baseprodashboard')

@section('content')


<div class="tabs-animation">
  <div class="row">
    <div class="col-12">
      <div class="card">
        @include('admin.dashboard.accesos_onsite-company-1')
      </div>
    </div>
  </div>
</div>

@include('admin.dashboard.estadisticas-company-1')

@endsection

@section('scripts')
<script src="assets/js/dashboard/querys.js"></script>
<script src="assets/js/dashboard/dashboard.js"></script>
@endsection