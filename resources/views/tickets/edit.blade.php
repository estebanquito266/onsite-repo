@extends('layouts.baseprocrud')

@section('content')
@include('tickets.top')

<div class="main-card mb-3 card">
    <div class="card-header">
        <h3 class="mr-3">Editar Ticket</h3>
    </div>
</div>

@if(!(Auth::user()->id==$ticket->user_owner_id))
    <input type="hidden" id="fieldset_setup" value="disable_fields">
@endif

{!!Form::open(['route'=>['ticket.update',$ticket->id], 'method'=>'PUT', 'files'=>true, 'id'=>'ticketForm'])!!}

@include('tickets.campos')

<div class="main-card mb-3 card">
    <div class="card-body">
        <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
        <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>
        <button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
        
{!!Form::close()!!}
        @if( Session::get('perfilAdmin'))
		{!!Form::button('Eliminar', ['class'=>'btn btn-danger btn-pill mt-2', 'id'=>'botonEliminarTicket' ] ) !!}
		<div class="text-center mt-2 d-none" id="divEliminarTicket">
			<span class="text-uppercase text-danger">¿Confirma la eliminación de este Ticket? </span>
			<br>
			{!!Form::open(['route'=>['ticket.destroy', $ticket->id], 'method'=>'DELETE', 'style'=>'display:inline;' ]) !!}
			{!!Form::submit('SI', ['class'=>'btn btn-danger btn-pill mt-2', 'id'=>'botonEliminarTicketSi' ] ) !!}
			{!!Form::close()!!}
			{!!Form::button('NO', ['class'=>'btn btn-focus btn-pill mt-2', 'id'=>'botonEliminarTicketNo' ] ) !!}
		</div>
		@endif
    </div>
</div>

@if(isset($ticket))
    @include('tickets.comment.campos')
@endif

@endsection
@section('scripts')	

@endsection

