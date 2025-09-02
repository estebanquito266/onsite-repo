@extends('layouts.baseprolist')

@section('content')


@include('tickets.top')

<div class="main-card mb-3 card">

    <div class="card-header">
        <h3 class="mr-3">Tickets</h3>









    </div>

    <div class="card-body">

        <div id="accordion" class="accordion-wrapper mb-3 myaccordion">

            <div class="card">
                <div id="headingFilter" class="card-header card-header-assurant card-header-tab"
                    style="cursor: pointer;padding: .7rem;">
                    <div style="width: 100%;"
                        class="myresponsive-text collapsed myaccordion-button simil-card-header card-header-title card-header-assurant font-size-lg text-capitalize text-capitalize-assurant font-weight-normal "
                        data-toggle="collapse" data-target="#collapseFilter1" aria-expanded="false"
                        aria-controls="collapseFilter">



                        <i class="header-icon pe-7s-filter ml-2 mr-3 text-muted opacity-6"> </i>
                        <div class="row row100" id="row100">
                            <div class="col-md-11" style="padding-top: 3px;font-size: 1.1rem !important;">

                                FILTROS
                            </div>
                            <div class="col-md-1">
                                <button
                                    class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-success btn-xs print-btn"
                                    style="display: none;"><i class="lnr-printer btn-icon-wrapper"> </i></button>

                            </div>
                        </div>
                    </div>

                </div>
                <div data-parent="#accordion" id="collapseFilter1" aria-labelledby="headingFilter" class="collapse"
                    style="">
                    <div class="card-body  pl-5 pb-5">
                        <form autocomplete="off" id="frmticketsfilter" method="POST"
                            action="{{(!isset($estado)?'filtrarTickets':($estado=='Cerrado'?'filtrarTicketsCerrados':'filtrarTickets'))}}"
                            accept-charset="UTF-8">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="estado" value="{{isset($estado)?$estado:''}}" />
                            <input type="hidden" name="filtroagil" value="{{isset($filtroagil)?$filtroagil:''}}"
                                id="filtroagil" />

                            <div class="form-row mt-3">
                                <div class="col-lg-3">
                                    <label for="type">Tipo</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" class="myplaceholder">-- Seleccione el Tipo --</option>
                                        @foreach($tipos as $tipoid => $tipoNombre)
                                        <option value="{{$tipoid}}">{{'['.$tipoid.'] '.$tipoNombre}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="type">Prioridades</label>
                                    <select name="priorities[]" id="type" class="form-control multiselect-dropdown"
                                        multiple="multiple">
                                        <option value="">-- Seleccione la prioridad --</option>
                                        @foreach($priorities as $priority)
                                        <option value="{{$priority->id}}" {{ !empty($priorities_selecteds) ?
                                            ((in_array($priority->id, $priorities_selecteds)) ? 'selected' : '') :
                                            ''}}>{{'['.$priority->id.'] '.$priority->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Texto</label>
                                        {!!Form::text('texto',null, ['class'=>'form-control','placeholder'=>'Ingrese el
                                        texto de su búsqueda '])!!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-3 col-md-3">
                                    <label for="motivo_consulta_ticket_id">Motivo consulta</label>
                                    <select name="reason_ticket_id" id="motivo_consulta_ticket_id" class="form-control">
                                        <option value="">-- Seleccione Motivo Consulta --</option>
                                        @if(isset($motivos_consulta))
                                        @foreach($motivos_consulta as $motivo_consulta)
                                        <option value="{{$motivo_consulta->id}}" {{ isset($ticket) && $motivo_consulta->
                                            id == $ticket->reason_ticket_id ? 'selected' : ''
                                            }}>{{'['.$motivo_consulta->id.'] '.$motivo_consulta->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mt-3">






                                <div class="form-group  col-lg-3 col-md-3">
                                    <label for="category_ticket_id">Categoría</label>
                                    <select name="category_ticket_id" id="category_ticket_id" class="form-control">
                                        <option value="">-- Seleccione Categoria --</option>
                                        @if(isset($categorias))
                                        @foreach($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{'['.$categoria->id.'] '.$categoria->name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>



                                <div class="form-group  col-lg-3 col-md-3">
                                    <label for="user_owner_id">Responsable</label>
                                    <select name="user_owner_id" id="user_owner_id" class="form-control">
                                        <option value="">-- Seleccione Usuario --</option>
                                        @if(isset($users))
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{'['.$user->id.'] '.$user->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group  col-lg-3 col-md-3">
                                    <label for="user_id">Usuario destino</label>
                                    <select name="user_receiver_id" id="user_id" class="form-control">
                                        <option value="">-- Seleccione Usuario --</option>
                                        @if(isset($users))
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{'['.$user->id.'] '.$user->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group  col-lg-3 col-md-3">
                                    <label for="group_user_receiver_id">Grupo destino</label>
                                    <select name="group_user_receiver_id" id="group_user_receiver_id"
                                        class="form-control">
                                        <option value="">-- Seleccione un Grupo --</option>
                                        @if(isset($grupos) )
                                        @foreach($grupos as $grupo)
                                        <option value="{{$grupo->id}}">{{'['.$grupo->id.'] '.$grupo->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>


                            </div>
                            <div class="form-row mt-3">


                            </div>
                            <div class="form-row mt-3">

                                <div class="form-group  col-lg-3 col-md-3">
                                    <label>Creado desde</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend datepicker-trigger">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        {!! Form::text('fecha_desde',null,['class'=>'form-control',
                                        'data-toggle'=>"datepicker", 'id'=>'fecha_desde', 'autocomplete'=>'off']) !!}
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-3">
                                    <label>Creado hasta</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        {!! Form::text('fecha_hasta',null,['class'=>'form-control',
                                        'data-toggle'=>"datepicker", 'id'=>'fecha_hasta', 'autocomplete'=>'off']) !!}
                                    </div>
                                </div>

                                <div class="form-group  col-lg-3 col-md-3">
                                    <label>Vencimiento desde</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend datepicker-trigger">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        {!! Form::text('fecha_vto_desde',null,['class'=>'form-control',
                                        'data-toggle'=>"datepicker", 'id'=>'fecha_vto_desde', 'autocomplete'=>'off'])
                                        !!}
                                    </div>
                                </div>
                                <div class="form-group col-lg-3 col-md-3">
                                    <label>Vencimiento hasta</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar-alt"></i>
                                            </div>
                                        </div>

                                        {!! Form::text('fecha_vto_hasta',null,['class'=>'form-control',
                                        'data-toggle'=>"datepicker", 'id'=>'fecha_vto_hasta', 'autocomplete'=>'off'])
                                        !!}
                                    </div>
                                </div>

                            </div>



                            <div class="row justify-content-end">
                                <div class="col-md-2">
                                    <button id="ticketsfilter" type="submit"
                                        class="btn btn-primary btn-block pull-right mt-1" name="boton"
                                        value="filtrar">Filtrar</button>
                                </div>

                                <div class="col-md-2">
                                    <a href="/ticket" class="btn btn-danger btn-block pull-right mt-1">Limpiar</a>
                                </div>

                                <div class="col-md-2">
                                    <a href="/exportarTickets"
                                        class="btn btn-success btn-block pull-right mt-1">Exportar</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>



        <div class="clearfix mb-3">
            <div id="filtro_agil" role="group" class="btn-group-sm btn-group btn-group-toggle float-right"
                data-toggle="buttons">
                <label class="btn btn-shadow btn-primary {{in_array('creadospormi',$checkeds) ? 'active' : ''}} mycheck"
                    data-toggle="tooltip" title="Tickets creados por este usuario.">
                    <input type="checkbox" name="filtroagilradio" value="creadospormi" id="creadospormi"
                        autocomplete="off" {{in_array('creadospormi',$checkeds) ? 'checked' : '' }}>
                    Míos
                </label>
                <label
                    class="btn btn-shadow btn-primary ml-1 mr-1 {{in_array('asignadosami',$checkeds) ? 'active' : ''}} mycheck"
                    data-toggle="tooltip" title="Tickets asignados a este usuario.">
                    <input type="checkbox" name="filtroagilradio" value="asignadosami" id="asignadosami"
                        autocomplete="off" {{in_array('asignadosami',$checkeds) ? 'checked' : '' }}>
                    Asignados
                </label>
                <label
                    class="btn btn-shadow btn-primary {{in_array('asignadosamigrupo',$checkeds) ? 'active' : ''}} mycheck"
                    data-toggle="tooltip" title="Tickets asignados a este Grupo de usuarios.">
                    <input type="checkbox" name="filtroagilradio" value="asignadosamigrupo" id="asignadosamigrupo"
                        autocomplete="off" {{in_array('asignadosamigrupo',$checkeds) ? 'checked' : '' }}>
                    Grupo
                </label>
            </div>
        </div>


        <table style="width: 100%;" id="ticketsdiv" class="table table-hover table-striped table-bordered "
            data-search="true">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Ingreso</th>
                    <th>Prioridad</th>
                    <th>Operación</th>
                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Dest. / Grupo</th>
                    <th>Categoría / Motivo</th>
                    <th>Expira</th>
                    <th>Status</th>
                    <th data-orderable="false" class="all"></th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($tickets as $ticket)
                @if(Session::get('perfilAdmin'))
                <tr>
                    <td>
                        {!! link_to_route('ticket.edit', $title = $ticket->id, $parameters = $ticket->id, $attributes =
                        null)!!}
                    </td>
                    <td>@if(isset($ticket->reparacion)) {{$ticket->reparacion->clave}} @else "" @endif</td>
                    <td>{{date('d/m/Y', strtotime($ticket->created_at))}}</td>
                    <td class="text-center">

                        @php
                        $priority_ticket = $ticket->priority_ticket ? strtolower(trim($ticket->priority_ticket->name)) :
                        "";
                        @endphp
                        @if ($priority_ticket === 'alta')
                        <button data-toggle="tooltip" title="Prioridad alta"
                            class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-danger btn-xs"><i
                                style="margin-top: -2px;" class="lnr-chevron-up-circle btn-icon-wrapper"> </i></button>
                        @elseif ($priority_ticket === 'media')
                        <button data-toggle="tooltip" title="Prioridad media"
                            class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-warning btn-sm"><i
                                style="margin-top: -2px;" class="lnr-circle-minus btn-icon-wrapper"> </i></button>
                        @elseif ($priority_ticket === 'baja')
                        <button data-toggle="tooltip" title="Prioridad baja"
                            class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-success btn-sm"><i
                                style="margin-top: -2px;" class="lnr-chevron-down-circle btn-icon-wrapper">
                            </i></button>

                        @else
                        <span class="mb-2 mr-2 badge badge-lg"
                            style='background-color:{{$ticket->priority_ticket?$ticket->priority_ticket->color:""}}; color:white; font-size: 10px; padding: 8px;'>
                            {{$ticket->priority_ticket?$ticket->priority_ticket->name:""}}
                        </span>
                        @endif

                    </td>
                    <td>{{str_replace("ion","",$ticket->getTypeName())}} {!! !empty($ticket->reparacion_id) ? ': <a
                            style="color:black;" target=" _blank"
                            href="/reparacion/'.$ticket->reparacion_id.'"><b>'.$ticket->reparacion_id.'</b></a>' :
                        (!empty($ticket->derivacion_id) ? ': <a style="color:black;" target=" _blank"
                            href="/derivacion/'.$ticket->derivacion_id.'"><b>'.$ticket->derivacion_id.'</b></a>': '')
                        !!}</td>
                    <td>{{$ticket->cliente ? $ticket->cliente->nombre : ($ticket->cliente_derivacion ?
                        $ticket->cliente_derivacion->nombre : "Sin Cliente")}}
                        @if(isset($ticket->reparacion->sucursal_onsite->razon_social))
                        {{ " - ".$ticket->reparacion->sucursal_onsite->razon_social ?? ''}} @endif</td>
                    <td>{{$ticket->user_owner ? $ticket->user_owner->name : '-'}}</td>
                    <td>{{$ticket->user_receiver ? $ticket->user_receiver->name : '-'}}<br>
                        {{$ticket->group_user_receiver?$ticket->group_user_receiver->name:'-'}}
                    </td>
                    <td>{{$ticket->category_ticket?$ticket->category_ticket->name:'-'}} / {{$ticket->motivo_consulta?
                        $ticket->motivo_consulta->name : '-'}}</td>
                    <td>{{$ticket->expiration_date?date('d/m/Y', strtotime($ticket->expiration_date)):' '}} -

                        @if($ticket->expiration_date)

                        <span class="badge badge-dot badge-dot-lg {{$ticket->semaforoclass}}" data-toggle="tooltip"
                            title="{{$ticket->semaforo}}">semaforo</span>
                        @endif
                    </td>
                    <td>{{($ticket->status_ticket) ? $ticket->status_ticket->name : ''}}</td>
                    <td class="text-center">
                        <div class="btn-actions-pane-right actions-icon-btn">
                            <div class="grupo_actions btn-group dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    class="btn-icon btn-icon-only btn btn-link"><i
                                        class="pe-7s-menu btn-icon-wrapper"></i></button>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu"
                                    style="position: flex; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);"
                                    x-placement="bottom-end">

                                    <div class="container">
                                        <div class="row">
                                            <a name='verTicket' data-toggle='modal'
                                                href="{{route('ticket.show',$ticket->id)}}" type="button" tabindex="0"
                                                class="dropdown-item aprobar_index_btn btn-find-ticket3"><i
                                                    class="dropdown-icon pe-7s-search"> </i><span>Ver Ticket</span></a>
                                        </div>
                                        <div class="row">
                                            <a name='editarTicket' data-toggle='modal'
                                                href="{{route('ticket.edit',$ticket->id)}}" type="button" tabindex="0"
                                                class="dropdown-item aprobar_index_btn btn-find-ticket"><i
                                                    class="dropdown-icon pe-7s-note"> </i><span>Editar Ticket</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @elseif((Auth::user()->group_ticket->contains($ticket->group_user_receiver_id))||
                (Auth::user()->id==$ticket->user_owner_id)||
                (Auth::user()->id==$ticket->user_receiver_id))
                <tr>
                    <td>
                        {!! link_to_route('ticket.edit', $title = $ticket->id, $parameters = $ticket->id, $attributes =
                        null)!!}
                    </td>
                    <td>{{date('d/m/Y', strtotime($ticket->created_at))}}</td>
                    <td>
                        <span class="mb-2 mr-2 badge badge-lg"
                            style='background-color:{{$ticket->priority_ticket?$ticket->priority_ticket->color:""}}; color:white; font-size: 10px; padding: 8px;'>
                            {{$ticket->priority_ticket?$ticket->priority_ticket->name:""}}
                        </span>
                    </td>
                    <td>{{$ticket->getTypeName()}}</td>
                    <td>{{$ticket->reparacion_id?$ticket->reparacion_id:$ticket->derivacion_id}}</td>
                    <td>{{$ticket->cliente ? $ticket->cliente->nombre : $ticket->cliente_derivacion->nombre}}</td>
                    <td>{{$ticket->user_owner ? $ticket->user_owner->name : '-'}}</td>
                    <td>{{$ticket->user_receiver ? $ticket->user_receiver->name : '-'}}<br>
                        {{$ticket->group_user_receiver?$ticket->group_user_receiver->name:'-'}}
                    </td>
                    <td>{{$ticket->category_ticket?$ticket->category_ticket->name:'-'}} / {{$ticket->motivo_consulta?
                        $ticket->motivo_consulta->name : '-'}}</td>
                    <td>{{$ticket->expiration_date?date('d/m/Y', strtotime($ticket->expiration_date)):' '}} -
                        @if($ticket->expiration_date)
                        <span class="badge badge-dot badge-dot-lg {{$ticket->semaforoclass}}" data-toggle="tooltip"
                            title="{{$ticket->semaforo}}">semaforo</span>
                        @endif

                    </td>
                    <td>{{($ticket->status_ticket) ? $ticket->status_ticket->name : ''}}</td>
                    <td class="text-center">
                        <div class="btn-actions-pane-right actions-icon-btn">
                            <div class="grupo_actions btn-group dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    class="btn-icon btn-icon-only btn btn-link"><i
                                        class="pe-7s-menu btn-icon-wrapper"></i></button>
                                <div tabindex="-1" role="menu" aria-hidden="true"
                                    class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu"
                                    style="position: flex; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);"
                                    x-placement="bottom-end">

                                    <div class="container">
                                        <div class="row">
                                            <a name='verTicket' data-toggle='modal'
                                                href="{{route('ticket.show',$ticket->id)}}" type="button" tabindex="0"
                                                class="dropdown-item aprobar_index_btn btn-find-ticket3"><i
                                                    class="dropdown-icon pe-7s-search"> </i><span>Ver Ticket</span></a>
                                        </div>
                                        <div class="row">
                                            <a name='editarTicket' data-toggle='modal'
                                                href="{{route('ticket.edit',$ticket->id)}}" type="button" tabindex="0"
                                                class="dropdown-item aprobar_index_btn btn-find-ticket"><i
                                                    class="dropdown-icon pe-7s-ticket"> </i><span>Editar
                                                    Ticket</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Ingreso</th>
                    <th>Prioridad</th>
                    <th>N° Operación</th>
                    <th>Cliente</th>
                    <th>Responsable</th>
                    <th>Dest. / Grupo</th>
                    <th>Categoría / Motivo</th>
                    <th>Expira</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <!---- PAGINATE -->

        @if( Request::path() =='ticket' )
        @include('pagination.default-limit-links', ['paginator' => $tickets, 'filters' => ''])
        @else
        @include('pagination.default-limit-links', ['paginator' => $tickets, 'filters' =>
        '','frmsubmit'=>'frmticketsfilter' ])
        @endif

        <!----  -->

    </div>
</div>


@endsection



@section('scripts')


<!-- DataTables JavaScript -->
{!!Html::script('vendor/datatables/js/jquery.dataTables.min.js')!!}
{!!Html::script('vendor/datatables-plugins/dataTables.bootstrap.min.js')!!}
{!!Html::script('vendor/datatables-responsive/dataTables.responsive.js')!!}


<script>
    document.addEventListener("DOMContentLoaded", function(event) {


        $(document).ready(function() {


            var table = $('#ticketsdiv').DataTable({
                            responsive: true,
                            searching: true,
                            info: false,
                            paging: false,
                            language: {
                                "url": "/js/Spanish.json"
                            },
                            drawCallback: function(settings) {
                                $('.dataTables_wrapper').removeClass('form-inline');
                            }
                        });

            $('.mycheck').click(function(){
                if($(this).hasClass('active')){
                    $('#filtroagil').val("");
                }else{
                    var mycheck = $(this).children('input').first();
                    $('#filtroagil').val(mycheck.val());
                }
                $('.mycheck').removeClass('active');
                $('form#frmticketsfilter').submit();
            });

            
            


            var creacion_date_desde = '<?php

use Illuminate\Support\Facades\Session;

 echo !empty($creacion_date_desde) ?  $creacion_date_desde : ""; ?>';
            var creacion_date_hasta = '<?php echo !empty($creacion_date_hasta) ?  $creacion_date_hasta : ""; ?>';

            var expiration_date_desde = '<?php echo !empty($expiration_date_desde) ?  $expiration_date_desde : ""; ?>';
            var expiration_date_hasta = '<?php echo !empty($expiration_date_hasta) ?  $expiration_date_hasta : ""; ?>';

            if (creacion_date_desde.length > 0) {
                $('#fecha_desde').val(creacion_date_desde);
            }

            if (creacion_date_hasta.length > 0) {
                $('#fecha_hasta').val(creacion_date_hasta);
            }

            if (expiration_date_desde.length > 0) {
                $('#fecha_vto_desde').val(expiration_date_desde);
            }

            if (expiration_date_hasta.length > 0) {
                $('#fecha_vto_hasta').val(expiration_date_hasta);
            }
/*

            $('#vtorange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#vtorange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('#creacionrange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#creacionrange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


*/

        });
    });
</script>

@include('layouts.base-echo')
@endsection


@section('css')

<!-- DataTables Responsive CSS -->
{!!Html::style('vendor/datatables-responsive/dataTables.responsive.css')!!}


<style>
    input::-moz-placeholder,
    textarea::-moz-placeholder {
        color: #c9c9c9 !important;
    }

    input:-ms-input-placeholder,
    textarea:-ms-input-placeholder {
        color: #c9c9c9 !important;
    }

    input::placeholder,
    textarea::placeholder {
        color: #c9c9c9 !important;
    }


    .row100 {
        width: 100%;
    }

    .mytab-header {
        display: flex;
        align-items: center;
        border-bottom-width: 1px;
        padding-top: 0;
        padding-bottom: 0;
        padding-right: .625rem;
        height: 3.5rem;
        padding: 0 !important;
    }


    .simil-card-header {
        color: #555f78eb;
    }

    .simil-card-header:not(.collapsed) {
        color: #000000b5;
    }


    .simil-card-header:hover {
        color: #000000b5;
        transition: transform 0.2s;
    }



    .myaccordion-button {
        display: flex;
    }

    .myaccordion-button::after {
        flex-shrink: 0;
        width: 1.35rem;
        height: 1.35rem;
        margin-left: auto;
        margin-right: 10px;
        content: "";
        background-image: url('/assets/images/down.svg');
        background-repeat: no-repeat;
        background-size: 1.25rem;
        transition: transform .2s ease-in-out;
    }

    .myaccordion-button:not(.collapsed)::after {
        background-image: url('/assets/images/down.svg');
        transform: rotate(-180deg);
    }

    .sem-gradient-danger {
        background-image: linear-gradient(140deg, #981a38 -30%, #d92550 90%);
        background-color: #981a38;
        border-color: #981a38;
        color: #fff;
        width: 20px !important;
        padding-top: 1px;
    }

    .sem-gradient-warning {
        background-image: linear-gradient(140deg, #c78f07 -30%, #f7b924 90%);
        background-color: #c78f07;
        border-color: #c78f07;
        color: #fff;
        width: 20px !important;
        padding-top: 1px;
    }

    .sem-gradient-success {
        background-image: linear-gradient(140deg, #298957 -30%, #3ac47d 90%);
        background-color: #298957;
        border-color: #298957;
        color: #fff;
        width: 20px !important;
        padding-top: 1px;
    }
</style>

@endsection