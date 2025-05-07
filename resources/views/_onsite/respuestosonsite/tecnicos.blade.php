@extends('layouts.baseprolist')

@section('content')


@include('_onsite.respuestosonsite.top')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="email" content="{{$email}}" />
<meta name="password" content="{{$password}}" />

<div class="main-card mb-3 card">
  <div class="card-header card_inicio_repuestos">
    <h3 class="mr-3">Acceso Técnicos Onsite</h3>    
    <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-filter"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-download"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#importador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-upload"></i>
    </button>
  </div>
  <div class="card-body">
    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
      <li class="nav-item">
        <a role="tab" class="nav-link active" id="botonapp" data-toggle="tab" href="#tab-content-0" aria-selected="true">
          <span>APP Onsite</span>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1" aria-selected="false">
          <span>Outline</span>
        </a>
      </li>
      <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2" aria-selected="false">
          <span>Outline 2x</span>
        </a>
      </li>
      <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
          <span>Dashed</span>
        </a>
      </li>
      <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
          <span>Gradients</span>
        </a>
      </li> -->
    </ul>




    <!---- PAGINATE -->


    <!----  -->

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/respuestos-onsite-form.js') !!}"></script>
@endsection