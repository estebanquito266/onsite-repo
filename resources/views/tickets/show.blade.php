@extends('layouts.baseprocrud')

@section('content')
@include('tickets.top')

<div class="main-card mb-3 card">
    <div class="card-header">
        <h3 class="mr-3">Detalle Ticket</h3>
    </div>
</div>
<fieldset disabled>
<input type="hidden" id="fieldset_setup" value="disable_fields">
{!!Form::open( ['id'=>'ticketForm'])!!}
@include('tickets.campos')



@if(isset($ticket))
    @include('tickets.comment.campos')
@endif
{!!Form::close()!!}
</fieldset>
@endsection
@section('scripts')	

@endsection