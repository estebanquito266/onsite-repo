<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Unidades Interiores Onsite</div>
        <div class="text-right col-lg-6 col-md-6">
            <a href="{!! URL::to('/unidadInterior/'.$sistemaEditar->id.'/createUnidadInterior') !!}" data-toggle="tooltip" title="Crear Unidad Exterior Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
    </div>
	<div class="card-body">
		<table style="width: 100%;" class="table table-hover table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>      
                    <th>Clave</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Dirección</th>
                </tr>
                </thead>
                <tbody class="small">
                @foreach($unidadesInteriores as $unidadInterior)
                    <tr>
                        <td><a href="{{ url('unidadInterior/' . $unidadInterior->id . '/editUnidadInterior'  ) }}">{{$unidadInterior->id}}</a></td>  
                        <td>{{$unidadInterior->clave}}</td>
                        <td>{{$unidadInterior->modelo}}</td>
                        <td>{{$unidadInterior->serie}}</td>
                        <td>{{$unidadInterior->direccion}}</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Clave</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Dirección</th>
                </tr>
                </tfoot>
            </table>
	</div>
</div>