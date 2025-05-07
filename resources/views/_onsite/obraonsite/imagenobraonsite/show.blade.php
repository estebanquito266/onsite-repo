@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.obraonsite.top')


<div class="main-card mb-3 card">
    <div class="card-header">
        <h3 class="mr-3">Imagen Obra Onsite</h3>
    </div>
</div>

<fieldset disabled>

@include('_onsite.obraonsite.imagenobraonsite.campos')

</fieldset>
</div>
</div>

@endsection

@section('scripts')
@endsection