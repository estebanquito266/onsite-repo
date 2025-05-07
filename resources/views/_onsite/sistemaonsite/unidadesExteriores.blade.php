<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Unidades Exteriores Onsite</div>
        <div class="text-right col-lg-6 col-md-6">
            <a href="{!! URL::to('/unidadExterior/'.$sistemaEditar->id.'/createUnidadExterior') !!}" data-toggle="tooltip" title="Crear Unidad Exterior Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
    </div>
	<div class="card-body">
		 <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>      
                    <th>Clave</th>                    
                    <th>Medida 1 a</th>
                    <th>Medida 1 b</th>
                    <th>Medida 1 c</th>
                    <th>Medida 1 d</th>
                    <th>Medida 2 a</th>
                    <th>Medida 2 b</th>
                    <th>Medida 2 c</th>
                    <th>Anclaje Piso</th>
                    <th>Contra Sifón</th>
                    <th>mm 500 Última Derivación Curva</th>
                </tr>
                </thead>
                <tbody class="small">
                @foreach($unidadesExteriores as $unidadExterior)
                    <tr>
                        <td><a href="{{ url('unidadExterior/' . $unidadExterior->id . '/editUnidadExterior'  ) }}">{{$unidadExterior->id}}</a></td>
                        <th>{{$unidadExterior->clave}}</th>
                        <td>{{$unidadExterior->medida_figura_1_a}}</td>
                        <td>{{$unidadExterior->medida_figura_1_b}}</td>
                        <td>{{$unidadExterior->medida_figura_1_c}}</td>
                        <td>{{$unidadExterior->medida_figura_1_d}}</td>
                        <td>{{$unidadExterior->medida_figura_2_a}}</td>
                        <td>{{$unidadExterior->medida_figura_2_b}}</td>
                        <td>{{$unidadExterior->medida_figura_2_c}}</td>                       
                        <td>@if($unidadExterior->anclaje_piso == 1) Si @else No @endif</td>
                        <td>@if($unidadExterior->contra_sifon == 1) Si @else No @endif</td>
                        <td>@if($unidadExterior->mm_500_ultima_derivacion_curva == 1) Si @else No @endif</td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th>#</th>    
                    <th>Clave</th>                
                    <th>Medida 1 a</th>
                    <th>Medida 1 b</th>
                    <th>Medida 1 c</th>
                    <th>Medida 1 d</th>
                    <th>Medida 2 a</th>
                    <th>Medida 2 b</th>
                    <th>Medida 2 c</th>
                    <th>Anclaje Piso</th>
                    <th>Contra Sifón</th>
                    <th>mm 500 Última Derivación Curva</th>
                    
                </tr>
                </tfoot>
            </table>

	</div>
</div>