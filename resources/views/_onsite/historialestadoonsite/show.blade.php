@extends('layouts.baseprocrud')

@section('content')

<div class="main-card mb-3 card">
  <div class="main-card mb-3 card">
    <div class="card-header">
      <h3 class="mr-3">Detalle notificación</h3>
    </div>
  </div>
</div>
<form action="{{ route('historialEstadoOnsite.update', ['historialEstadoOnsite' => $historialEstadoOnsite ]) }}" method='POST' id='historialEstadoOnsiteForm'>
  <fieldset disabled>
    @include('_onsite.historialestadoonsite.campos')
  </fieldset>
</form>

@endsection

@section('scripts')
<script src="/assets/js/_onsite/historial-estados-onsite-form.js"></script>
@endsection