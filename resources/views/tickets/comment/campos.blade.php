<div class="main-card mb-3 card" id="comentariosForm">
    <div class="card-header bg-secondary"></div>
    <div class="card-body">
        {!!Form::open(['route'=>'commentTicket.store', 'method'=>'POST', 'files'=>true, 'id'=>'commentForm'])!!}
        {!!Form::hidden('user_id', Auth()->user()->id,['id'=>'comment_user_id'])!!}
        {!!Form::hidden('ticket_id',(isset($ticket) ?$ticket->id:''),['id'=>'comment_ticket_id'])!!}
        {!!Form::hidden('_modalComment', "1",['id'=>'_modalComment'])!!}
            <div class="form-row mt-3">
                <div class="form-group col-md-12">
                    <label>Comentarios</label>
                    {!!Form::textarea('comentario',null,['class'=>'form-control','placeholder'=>'Ingrese un nuevo comentario...','id'=>'comentario_id','rows'=>'3','required'])!!}
                </div>
            </div>
            <div class="form-row mt-3">
            <div class="form-group col-md-12">
                    <label>Archivo</label>
                    {!!Form::file('file',null,['class'=>'form-control-file','id'=>'file'])!!}
            </div>
            </div>
            <div class="form-row mt-3">
                <div class="form-group col-md-6">
                    <button type="submit" class="btn btn-primary btn-pill mt-2" name="crearComentario" value="1">Crear</button>
                
                <div class="dropdown d-inline-block">
                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-pill mt-2" name="botonGuardarEstado" value="1" id="button-cambiar">Crear y Cambiar Estado</button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-rounded dropdown-menu">
                    @if(isset($status))
                    @foreach($status as $st)
                    <button type="submit" tabindex="0" class="dropdown-item" name="status_id" value="{{ $st->id }}">Nuevo Estado: {{ $st->name }}</button>
                    @endforeach
                    @endif
                </div>
                </div>
		</div>
            </div>
        {!!Form::close()!!}
        <div>

            </div>
        </div>  
    </div>
    <div class="main-card mb-3 card " id="comentariosList">
        <div class="card-header bg-secondary"></div>
        <div class="card-body">
        <table style="width: 100%;" class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Comentario</th>
                    <th>Archivo</th>
                    <th id="eliminarColumn">Eliminar</th>
                </tr>
            </thead>
            <tbody class="small" id="comentariosBody">
                @if(isset($commentsTickets))
                @forelse($commentsTickets as $comentario)
                <tr>
                    <td>{{$comentario->id}}</td>
                    <td>{{$comentario->user->name}}</td>
                    <td>{{$comentario->comment}}</td>
                    <td>
                    @if($comentario->file!="")
                        <a download="{{$comentario->file}}" href="{{'/files/'.$comentario->file}}" title="{{$comentario->file}}">{{$comentario->file}}</a>
                    @else
                        <p>No se han encontrado archivos</p>
                    @endif
                    </td>
                    <td>
                    {!!Form::open(['route'=>['commentTicket.destroy', $comentario->id], 'method'=>'DELETE', 'style'=>'display:inline;']) !!}
                        <button type="submit" class="mb-2 mr-2  btn btn-link text-danger "><i class="fa fa-times-circle fa-2x"></i></button>
                    {!!Form::close()!!}
                    </td>
                </tr>
                @empty
                    <tr>
                    <td><span class="">Ningún dato disponible en esta tabla-</span></td>
                    </tr>
                @endforelse
                @endif
                
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Comentario</th>
                    <th>Archivo</th>
                    <th>Eliminar</th>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>  
</div>
