<?php $isEditable = isset($viewMode) && $viewMode == 'edit'; ?>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Sistemas</div>

    </div>
    <div class="card-body row">
        <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre Sistema</th>
                    <th>Comentarios</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($sistemas as $sistema)
                <tr>
                    <td>
                        <?php if($isEditable) :  ?>
                            <a href="{{ url('sistemaOnsite/' . $sistema->id . '/edit') }}">{{$sistema->id}}</a>
                        <?php else:  ?>
                            {{$sistema->id}}
                        <?php endif;  ?>                                                
                    </td>
                    <td>{{$sistema->nombre}}</td>
                    <td>{{$sistema->comentario}}</td>
                    <td>
                        <span class="mr-2"><a href="{{ route('sistemaOnsite.show',$sistema->id) }}"><i class="fa fa-eye fa-2x"></i></a></span>
                    </td>                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>