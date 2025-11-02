@extends('layouts.baseprocrud')


@section('content')
@include('tickets.top')

<div class="main-card mb-3 card">
    <div class="card-header">
        <h3 class="mr-3">Ticket por grupo</h3>
    </div>
</div>

{!!Form::open(['route'=>'ticket.store', 'method'=>'POST', 'files'=>true, 'id'=>'notaForm'])!!}

@include('tickets.campos2_by_group')


@include('layouts.sticky',[
    'buttons'=>[
            [
                'type'=>'submit',
                'bootstyle'=>'primary',
                'name'=>'botonGuardar',
                'value'=>'1',    
                'text'=>'Guardar',    
            ],
            [
                'type'=>'submit',
                'bootstyle'=>'primary',
                'name'=>'botonGuardarCerrar',
                'value'=>'1',    
                'text'=>'Guardar y Cerrar',    
            ],
            [
                'type'=>'reset',
                'bootstyle'=>'secondary',
                'text'=>'Resetear',    
            ]
        
        ]
    ])


{!!Form::close()!!}


@endsection



@section('scripts')	
@endsection
