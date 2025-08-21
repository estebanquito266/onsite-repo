
<div class="main-card mb-3 card ">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span>Ticket</span>
        
        <a data-toggle="tooltip" data-placement="left" title="Nuevo ticket" href="/ticketrep/{{ $reparacionOnsite->id }}" target="_BLANK" type="button" class="btn btn-success aprobar_index_btn btn-find-ticket1"><i class="fas fa-plus"></i></a>

    </div>
    <div class="card-body">

    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>Ingreso</th>
                    <th>Prioridad</th>
                    <th>Responsable</th>
                    <th>Destin / Grupo</th>
                    <th>Categoría / Motivo</th>
                    <th>Expiración</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody class="small">
                @if($tickets)
                @foreach($tickets as $ticket)
                <tr>
                    <td>{{date('d/m/Y', strtotime($ticket->created_at))}}</td>
                    <td>
                    <span class="mb-2 mr-2 badge badge-lg" style='background-color:{{$ticket->priority_ticket?$ticket->priority_ticket->color:""}}; color:white; font-size: 10px; padding: 8px;'>
                        {{$ticket->priority_ticket?$ticket->priority_ticket->name:""}}
                    </span>
                    </td>
                    <td>{{$ticket->user_owner ? $ticket->user_owner->name : '-'}}</td>
                    <td>{{$ticket->user_receiver ? $ticket->user_receiver->name : '-'}}<br>
                    {{$ticket->group_user_receiver?$ticket->group_user_receiver->name:'-'}}</td>
                    <td>{{$ticket->category_ticket?$ticket->category_ticket->name:'-'}} {{$ticket->motivo_consulta? $ticket->motivo_consulta->name : '-'}}</td>
                    <td>{{$ticket->expiration_date?date('d/m/Y', strtotime($ticket->expiration_date)):'-'}}</td>
                    <td>{{($ticket->status_ticket)?$ticket->status_ticket->name:'-'}}</td>
                    <td >
						<div class="btn-actions-pane-right actions-icon-btn">
                            <div class="grupo_actions btn-group dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: flex; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">                                
                                <div class="container">
                                    <div class="row">
                                        <a name='verComentarios' data-toggle='modal' data-target='#modalCrearTicket' data-reparacion-id="{{ $reparacionOnsite->id }}" data-cliente-id="{{ $reparacionOnsite->cliente_id }}" data-user-id="{{$reparacionOnsite->user_id}}" data-ticket-id="{{$ticket->id}}" data-logged-user-id="{{Session::get('idUser')}}" value="{{$reparacionOnsite->id}}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn btn-find-ticket5"><i class="dropdown-icon pe-7s-search"> </i><span>Ver Detalles</span></a>
                                    </div>
                                    
                                </div>
                                </div>
                            </div>
					    </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th>Ingreso</th>
                    <th>Prioridad</th>
                    <th>Responsable</th>
                    <th>Destin / Grupo</th>
                    <th>Categoría / Motivo</th>
                    <th>Expiración</th>
                    <th>Status</th>
                    <th>#</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
