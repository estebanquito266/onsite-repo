<?php $isEditable = isset($viewMode) && $viewMode == 'edit'; ?>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Esquema</div>
        <div class="text-right col-lg-6 col-md-6">      

            <?php if($isEditable) :  ?>
            <a href="{{url ('imagenobraonsite/create?obra_onsite_id=' . $obraOnsite->id)}}" data-toggle="tooltip" title="Crear Unidad Exterior Onsite" 
                data-placement="bottom" class="btn-shadow mr-3 btn btn-success"><i class="fa fa-plus"></i></a>
            <?php endif;  ?>
        </div>
    </div>
    <div class="card-body row">
        <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Previsualización</th>
                    
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($imagenes as $imagen)
                <tr>
                    <td>
                        <?php if($isEditable) :  ?>
                            <a href="{{ url('imagenobraonsite/' . $imagen->id . '/edit'  ) }}">{{$imagen->id}}</a>
                        <?php else:  ?>
                            {{$imagen->id}}
                        <?php endif;  ?>
                    </td>
                    
                    <td class="text-center">
                        <?php if(in_array(strtolower(pathinfo($imagen->archivo, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) :  ?>
                            <img src="{{ asset('/imagenes/reparaciones_onsite/'.$imagen->archivo) }}" width="60px" height="60px" style="border-radius: 100%">
                        <?php else:  ?>
                            <small>Sin previsualización</small>
                        <?php endif;  ?>         
                    </td>
                   
                    <td>
                        <span class="mr-2"><a href="{{ asset('/imagenes/reparaciones_onsite/'.$imagen->archivo) }}" download=""><i class="fa fa-download fa-2x"></i></a></span>
                        <span class="mr-2"><a href="{{ route('imagenobraonsite.show',$imagen->id) }}"><i class="fa fa-eye fa-2x"></i></a></span>
                        <?php if($isEditable) :  ?>
                        <span><a href="{{ route('imagenobraonsite.edit',$imagen->id) }}"><i class="fa fa-edit fa-2x"></i></a></span>
                        <?php endif;  ?>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>