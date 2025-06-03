<div class="main-card mb-3 card ">
    @include('layouts.encabezadocards', ['title'=>'Información general', 'icono'=>'pe-7s-ticket'])
    <div class="card-body ">

        <div class="row">
            <div class="col-md-6">
                <div class="form-group col-md-12">
                    {!!Form::hidden('user_owner_id', Auth()->user()->id,['id'=>'user_owner_id'])!!}
                    <input type="hidden" id="ticket_id" value="{{isset($ticket)?$ticket->id:''}}">
                    <input type="hidden" id="type" name="type">
                    <label for="tipo_ticket">Tipo:</label>
                    <select name="tipo_ticket" id="tipo_ticket" class="form-control" {{(isset($ticket_tipo))?'disabled':''}} {{(isset($ticket))?'disabled':''}}>

                        @foreach($tipos as $tipoid => $tipoNombre)
                        <option value="{{$tipoid}}" {{isset($ticket_tipo)?(($ticket_tipo==$tipoid) ? 'selected' : '') : ''}} {{isset($ticket)?(($ticket->type==$tipoid) ? 'selected' : '') : ''}}>{{'['.$tipoid.'] '.$tipoNombre}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-12" id="ticket_reparacion_id_create">
                    <label for="reparacion_id">Reparación:</label>
                    @if(isset($rep_id))
                    {!!Form::text('reparacion_id',$rep_id, ['class'=>'form-control','placeholder'=>'Ingrese ID de Reparación a buscar...','id'=>'reparacion_id', 'readonly'])!!}
                    @else
                    {!!Form::text('reparacion_id',(isset($ticket)?($ticket->reparacion_id!=0?$ticket->reparacion_id:null):null), ['class'=>'form-control','placeholder'=>'Ingrese ID de Reparación a buscar...','id'=>'reparacion_id'])!!}
                    @endif
                    <span class="help-block badge badge-secondary" id="reparacionMsg"></span>
                </div>

                <div class="form-group col-md-12" id="ticket_derivacion_id_create">
                    <label for="derivacion_id">Derivación:</label>
                    @if(isset($der_id))
                    {!!Form::text('derivacion_id',$der_id, ['class'=>'form-control','placeholder'=>'Ingrese ID de Derivación a buscar...','id'=>'derivacion_id', 'readonly'])!!}
                    @else
                    {!!Form::text('derivacion_id',(isset($ticket)?($ticket->derivacion_id!=0?$ticket->derivacion_id:null):null), ['class'=>'form-control','placeholder'=>'Ingrese ID de Derivación a buscar...','id'=>'derivacion_id'])!!}
                    @endif
                    <span class="help-block badge badge-secondary" id="derivacionMsg"></span>
                </div>


                <div class="form-group col-md-12" id="div_buscar_cliente_reparacion">
                    <label>Buscar Cliente:</label>
                    <div class="form-group input-group ">
                        @if(isset($cliente_reparacion))
                        <input class="form-control" id="textoBuscarCliente" name="textoBuscar" type="text" value="{{$cliente_reparacion->nombre}}" disabled>
                        @elseif(isset($ticket))
                        {!!Form::text('textoBuscar',($ticket->type==1?$ticket->cliente->nombre:""), ['class'=>'form-control','placeholder'=>'Ingrese dni/cuit o nombre del cliente a buscar','id'=>'textoBuscarCliente'])!!}
                        @else
                        {!!Form::text('textoBuscar',null, ['class'=>'form-control','placeholder'=>'Ingrese dni/cuit o nombre del cliente a buscar','id'=>'textoBuscarCliente'])!!}
                        @endif
                        @if(!isset($cliente_reparacion))
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="buscarCliente"><i class="fa fa-search"></i></button>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group col-md-12" id="div_buscar_cliente_derivacion">
                    <label>Buscar Cliente Derivación:</label>
                    <div class="form-group input-group ">
                        @if(isset($cliente_derivacion))
                        <input class="form-control" id="textoBuscarClienteDerivacion" name="textoBuscar" type="text" value="{{$cliente_derivacion->nombre}}" disabled>
                        @elseif(isset($ticket))
                        {!!Form::text('textoBuscar',($ticket->type==2?$ticket->cliente_derivacion->nombre:null), ['class'=>'form-control','placeholder'=>'Ingrese dni/cuit o nombre del cliente a buscar','id'=>'textoBuscarClienteDerivacion'])!!}
                        @else
                        {!!Form::text('textoBuscar',null, ['class'=>'form-control','placeholder'=>'Ingrese dni/cuit o nombre del cliente a buscar','id'=>'textoBuscarClienteDerivacion'])!!}
                        @endif

                        @if(!isset($cliente_derivacion))
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" id="buscarClienteDerivacion"><i class="fa fa-search"></i></button>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group col-md-12" id="div_cliente_id">
                    <label>Cliente:</label>
                    <select name="cliente_id" id="cliente_id" class="form-control">
                        <option value="">Seleccione el cliente</option>
                        @if(isset($cliente_reparacion))
                        <option value="{{$cliente_reparacion->id}}" selected>{{$cliente_reparacion->nombre}} - {{$cliente_reparacion->dni_cuit}}</option>
                        @elseif(isset($cliente_derivacion))
                        <option value="{{$cliente_derivacion->id}}" selected>{{$cliente_derivacion->nombre}} - {{$cliente_derivacion->dni_cuit}}</option>
                        @elseif(isset($ticket) && !empty($ticket->type))
                        <option value="{{($ticket->type==1?$ticket->cliente->id:$ticket->cliente_derivacion->id)}}" selected>{{($ticket->type==1?$ticket->cliente->nombre:$ticket->cliente_derivacion->nombre)}} - {{$ticket->type==1?$ticket->cliente->dni_cuit:""}}</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-6">

                <label>Destinatarios:</label><br>


                <div role="group" class="mb-2  btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-shadow btn-outline-2x btn-outline-primary cliente_grupo">
                        <input type="radio" name="options" data-id="radio_usuario" autocomplete="off" checked="">
                        USUARIO
                    </label>
                    <label class="btn btn-shadow btn-outline-2x btn-outline-primary cliente_grupo">
                        <input type="radio" name="options" data-id="radio_grupo" autocomplete="off" checked="">
                        GRUPO
                    </label>

                </div>


                <div class="form-row mt-3 select_usuario_grupo" id="radio_usuario">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-12">
                            <div class="form-group" id="ticket_user_receiver_id_create">
                                <label for="user_id">Usuario Destino:</label>
                                <select name="user_receiver_id" id="user_id" class="form-control">
                                    <option value="">-- Seleccione Usuario --</option>
                                    @if(isset($ticket))
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}" {{$user->id == $ticket->user_receiver_id ? 'selected' : '' }}>{{'['.$user->id.'] '.$user->name}}</option>
                                    @endforeach
                                    @else
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{'['.$user->id.'] '.$user->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="form-row mt-3 select_usuario_grupo" id="radio_grupo">


                    <div class="row" style="width: 100%;">
                        <div class="col-sm-12">
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
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
</div>


<div class="main-card mb-3 card ">
    @include('layouts.encabezadocards', ['title'=>'Detalle del caso', 'icono'=>'pe-7s-note2'])
    <div class="card-body">
        <div class="row">

            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_status_ticket_id_create">
                    <label for="status_ticket_id">Status:</label>
                    <select name="status_ticket_id" id="status_ticket_id" class="form-control" disabled>
                        <option value="">-- Seleccione Status --</option>
                        @if(isset($status))
                        @foreach($status as $stat)
                        @if(isset($ticket))
                        <option value="{{$stat->id}}" {{ $stat->id == $ticket->status_ticket_id ? 'selected' : '' }}>{{'['.$stat->id.'] '.$stat->name}}</option>
                        @else
                        <option value="{{$stat->id}}" {{ $stat->name==="Nuevo" ? 'selected' : '' }}>{{'['.$stat->id.'] '.$stat->name}}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_priority_ticket_id_create">
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
            </div>

        </div><!--fin row-->
        <div class="row">
            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_reason_ticket_id_create">
                    <label for="motivo_consulta_ticket_id">Motivo:</label>
                    <select name="reason_ticket_id" id="motivo_consulta_ticket_id" class="form-control" required>
                        <option value="">-- Seleccione Motivo Consulta --</option>
                        @if(isset($motivos_consulta))
                        @foreach($motivos_consulta as $motivo_consulta)
                        <option value="{{$motivo_consulta->id}}" {{ isset($ticket) && $motivo_consulta->id == $ticket->reason_ticket_id ? 'selected' : '' }}>{{'['.$motivo_consulta->id.'] '.$motivo_consulta->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_category_ticket_id_create">
                    <label for="category_ticket_id">Categoría:</label>
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
            </div>
        </div><!--fin row-->
        <hr>
        <div class="row">
            @if(isset($ticket))
            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_created_at">
                    <label for="created_at">Fecha de Creación:</label>
                    @if(isset($ticket))
                    {!!Form::date('created_at',date('Y-m-d', strtotime($ticket->created_at)), ['class'=>'form-control','id'=>'created_at','disabled'])!!}
                    @else
                    {!!Form::date('created_at',null, ['class'=>'form-control','id'=>'created_at'])!!}
                    @endif
                </div>
            </div>
            @endif
            <div class="col-6">
                <div class="form-group  col-md-12" id="ticket_expiration_date_create">
                    <label for="expiration_date">Expiración:</label>
                    @if(isset($ticket))
                    {!!Form::date('expiration_date',($ticket->expiration_date?date('Y-m-d', strtotime($ticket->expiration_date)):null), ['class'=>'form-control','id'=>'expiration_date'])!!}
                    @else
                    {!!Form::date('expiration_date',null, ['class'=>'form-control','id'=>'expiration_date'])!!}
                    @endif
                </div>
            </div>
        </div><!--fin row-->
        <hr>

        @if(isset($ticket))
        <div class="row">
            <div class="col-6">
                <div class="form-group  col-md-12 mr-3" id="ticket_user_owner_id_show">
                    <label for="user_owner">Usuario Responsable:</label>
                    {!!Form::text('user_owner',$ticket->user_owner->name,['class'=>'form-control mb-1','id'=>'user_owner','disabled'])!!}
                </div>
            </div>
        </div>
        <hr>
        @endif
        <div class="row">
            <div class="col-6">
                <div class="form-group  col-md-12 mr-3" id="ticket_file_create">
                    <div class="form-group mr-3">
                        {!!Form::file('ticket_file',null,['class'=>'form-control-file','id'=>'ticket_file'])!!}
                    </div>
                    @if(isset($ticket))
                    @if(isset($ticket->file))
                    <div class="form-group mr-3">
                        <a download="{{$ticket->file}}" href="{{'/files/'.$ticket->file}}" title="{{$ticket->file}}" class="btn btn-primary">
                            <i class="fas fa-download"></i> Descargar Archivo
                        </a>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div><!--fin row-->

        <div class="row">
            <div class="col-md-12">
            <div class="form-group  col-md-12 mt-3">
                                <label>Detalle:</label>
                                <textarea name="detail" id="descripcion" cols="30" rows="5" class="form-control">{{ isset($ticket) ? $ticket->detail : '' }}</textarea>
                        </div>
            </div>
        </div><!--fin row-->

    </div>
</div>

@section('scripts')
{!!Html::script('js/clientes-tickets.js')!!}
{!!Html::script('js/derivaciones.js')!!}
{!!Html::script('js/reparaciones.js')!!}
{!!Html::script('/assets/js/tickets/ticket-form.js')!!}
@endsection