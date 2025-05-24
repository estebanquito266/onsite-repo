<div class="main-card mb-3 card" id="div_modal_campos">
    <div class="card-body">
        <div class="form-row mt-3">
        <div class="col-lg-12 col-md-12">
                <!-- Campos Ocultos -->
                {!!Form::hidden('user_owner_id', Auth()->user()->id,['id'=>'user_owner_id'])!!}
                {!!Form::hidden('_modalTicket', "1",['id'=>'_modalTicket'])!!}
                {!!Form::hidden('type', null,['id'=>'type'])!!}
                
                <div id="ticket_cliente_create" style="display: none">
                    <label>Cliente:</label>
                    <input class="form-control" id="cliente_id" name="cliente_id" type="text" readonly="readonly"> 
                </div>

                <div id="ticket_cliente_show" style="display: none">
                    <input class="form-control" id="cliente_id" name="cliente_id" type="text" readonly="readonly"> 
                </div>
                
                <div class="form-group" id="ticket_reparacion_id_create" style="display: none">
                    <label for="reparacion_id">Reparación:</label>
                    {!!Form::text('reparacion_id',(isset($ticket)?($ticket->reparacion_id!=0?$ticket->reparacion_id:null):null), ['class'=>'form-control','placeholder'=>'Ingrese ID de Reparación a buscar...','id'=>'reparacion_id'])!!}		
                    <span class="help-block badge badge-secondary" id="reparacionMsg"></span>  
                </div>
                <div id="ticket_reparacion_id_show" style="display: none"></div>

                <div class="form-group" id="ticket_derivacion_id_create" style="display: none">
                    <label for="derivacion_id">Derivación:</label>
                    {!!Form::text('derivacion_id',(isset($ticket)?($ticket->derivacion_id!=0?$ticket->derivacion_id:null):null), ['class'=>'form-control','placeholder'=>'Ingrese ID de Derivación a buscar...','id'=>'derivacion_id'])!!}		
                    <span class="help-block badge badge-secondary" id="derivacionMsg"></span>  
                </div>
                <div class="form-group" id="ticket_derivacion_id_show" style="display: none">
                </div>
                <!-- Fin Campos Ocultos -->
                <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group" id="ticket_user_receiver_id_create">
                        <label for="user_receiver_id">Usuario Destino:</label>
                        <select name="user_receiver_id" id="user_id" class="form-control">
                            <option value="">-- Selecciona Usuario --</option>
                            @if(isset($ticket))
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->id == $ticket->user_receiver_id ? 'selected' : '' }}>{{'['.$user->id.'] '.$user->name}}</option>
                                @endforeach
                            @else
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" >{{'['.$user->id.'] '.$user->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group" id="ticket_user_receiver_id_show">
                    </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group" id="ticket_group_user_receiver_id_create">
                        <label for="group_user_receiver_id">Grupo Usuario Destino:</label>
                        <select name="group_user_receiver_id" id="group_user_receiver_id" class="form-control">
                        <option value="">-- Seleccione un Grupo --</option>
                            @if(isset($grupos) && count($grupos) > 0)
                                @if(isset( $ticket))
                                    @foreach($grupos as $grupo)
                                        <option value="{{$grupo->id}}" {{ $grupo->id == $ticket->group_user_receiver_id ? 'selected' : '' }}>{{'['.$grupo->id.'] '.$grupo->name}}</option>
                                    @endforeach
                                @else
                                    @foreach($grupos as $grupo)
                                        <option value="{{$grupo->id}}">{{'['.$grupo->id.'] '.$grupo->name}}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                    <div class="form-group" id="ticket_group_user_receiver_id_show">
                    </div>
                    </div>
                    
                </div><hr/><!--fin row-->
                <div class="row">
                    <div class="col-6">
                        <div class="form-group" id="ticket_priority_ticket_id_create">
                        <label for="priority_ticket_id">Prioridad:</label>
                        <select name="priority_ticket_id" id="priority_ticket_id" class="form-control" required>
                            <option value="">-- Seleccione Prioridad --</option>
                            @if(isset($priorities))
                                @foreach($priorities as $priority)
                                    <option value="{{$priority->id}}" {{ isset($ticket) && $priority->id == $ticket->priority_ticket_id ? 'selected' : '' }}>{{'['.$priority->id.'] '.$priority->name}}</option>
                                @endforeach
                            @endif
                        </select>
                        </div>
                        <div class="form-group" id="ticket_priority_ticket_id_show">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group" id="ticket_status_ticket_id_create">
                            <label for="status_ticket_id">Status:</label>
                            <select name="status_ticket_id" id="status_ticket_id" class="form-control" disabled>
                                <option value="">-- Seleccione Status --</option>
                                @if(isset($status))
                                    @foreach($status as $stat)
                                        <option value="{{$stat->id}}" {{ isset($ticket) && $stat->id == $ticket->status_ticket_id ? 'selected' : '' }}>{{'['.$stat->id.'] '.$stat->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group" id="ticket_status_ticket_id_show">
                        </div>
                    </div>
                    </div><!--fin row-->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group" id="ticket_reason_ticket_id_create">
                                <label for="motivo_consulta_ticket_id">Motivos Consulta:</label>
                                <select name="reason_ticket_id" id="motivo_consulta_ticket_id" class="form-control" required>
                                    <option value="">-- Seleccione Motivo Consulta --</option>
                                    @if(isset($motivos_consulta))
                                        @foreach($motivos_consulta as $motivo_consulta)
                                            <option value="{{$motivo_consulta->id}}" {{ isset($ticket) && $motivo_consulta->id == $ticket->reason_ticket_id ? 'selected' : '' }}>{{'['.$motivo_consulta->id.'] '.$motivo_consulta->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group" id="ticket_reason_ticket_id_show">
                            </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group" id="ticket_category_ticket_id_create">
                                    <label for="category_ticket_id">Categoria Ticket:</label>
                                    <select name="category_ticket_id" id="category_ticket_id" class="form-control">
                                        <option value="">-- Seleccione Categoria --</option>
                                        @if(isset($categorias) && count($categorias) > 0)
                                            @if(isset($ticket))
                                                @foreach($categorias as $categoria)
                                                    <option value="{{$categoria->id}}" {{ $categoria->id == $ticket->category_ticket_id ? 'selected' : '' }}>{{'['.$categoria->id.'] '.$categoria->name}}</option>
                                                @endforeach
                                            @else
                                                @foreach($categorias as $categoria)
                                                    <option value="{{$categoria->id}}">{{'['.$categoria->id.'] '.$categoria->name}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group" id="ticket_category_ticket_id_show">
                                </div>
                            </div>
                        </div><hr/><!--fin row-->
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group" id="ticket_created_at_show" style="display: none">
                                    <label for="created_at">Fecha de Creación:</label>
                                    {!!Form::text('created_at',null, ['class'=>'form-control','id'=>'created_at','disabled'])!!}
                                </div>
                                <div class="form-group" id="ticket_expiration_date_create" style="display: none"></div>
                            </div>
                            <div class="col-6">
                                <div class="form-group" id="ticket_expiration_date_show" style="display: none">
                                    <label for="expiration_date">Fecha de Expiración:</label>
                                    @if(isset($ticket))
                                        {!!Form::text('expiration_date',($ticket->expiration_date?date('Y-m-d', strtotime($ticket->expiration_date)):""), ['class'=>'form-control','id'=>'expiration_date'])!!}
                                    @else
                                        {!!Form::text('expiration_date',null, ['class'=>'form-control','id'=>'expiration_date','disabled'])!!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="form-group col-md-6" id="ticket_user_owner_id_show" style="display: none">
                            <label for="user_owner">Usuario Responsable:</label>
                            {!!Form::text('user_owner',null,['class'=>'form-control','id'=>'user_owner','disabled'])!!}
                        </div>
                        </div><hr/><!--fin row-->
                </div>
                <div class="col-lg-12 col-md-12">
                    <!-- Columna única para el campo "Detalle" -->
                    <div class="form-group" id="ticket_detalle_create">
                        <label>Detalle</label>
                        <textarea name="detail" id="descripcion" cols="30" rows="5" class="form-control">{{ isset($ticket) ? $ticket->detail : '' }}</textarea>
                        <hr/><!--fin row-->
                    </div>
                    <div class="form-group" id="ticket_detalle_show">
                    </div>
                </div>
                <div class="form-group col-md-12 mr-3"" id="ticket_file_create">
                    <div class="row">
                        <div class="form-group col-md-6 mr-3"">
                        {!!Form::file('ticket_file',null,['class'=>'form-control-file','id'=>'ticket_file'])!!}
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12 mr-3"" id="ticket_file_show">
                    <div class="row">
                    <div class="form-group col-md-6 mr-3"">
                    <a download="" href="#" title="" class="btn btn-primary" id="file_download">
                        <i class="fas fa-download"></i> Descargar Archivo
                    </a>
                    </div>
                    </div>
                </div>
                <div class="form-row mt-3" id="ticket_editar_btn_show" style="display: none">
                    <div class="form-group col-lg-12 col-md-12">
                        <a href="#" class="btn btn-primary btn-pill mt-2" name="editar" id="editar_btn">Editar Ticket</a>
		        </div>
            </div>
        </div>
    </div>
</div>



@section('scripts')
{!!Html::script('js/clientes-tickets.js')!!}  
</script>
   
@endsection