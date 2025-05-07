@extends('layouts.baseprolist')

@section('content')


@include('_onsite.terminalonsite.top')

<div class="main-card mb-3 card">

	<div class="card-header">
		<h3 class="mr-3">Listado de Unidades Exterior Onsite</h3>
		<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button>
	</div>

	<div class="card-body">

		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarTerminalOnsite') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-row mt-3">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese texto </label>
							
						</div>
					</div>

					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese sucursal onsite clave/razón social </label>
							
						</div>
					</div>

					<div class=' col-lg-12'>
						<div class='form-group'>
							<button type="submit" class="btn btn-primary btn-block btn-pill pull-right">Filtrar</button>
						</div>
					</div>
				</div>

			</form>
		</div>

		<div class="collapse border mb-5" id="exportador">
			<div class="form-group text-center">
				<a href="exports/listado_terminalonsite_{{ $user_id }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
			</div>
		</div>

		 <div class="main-card mb-3 card">
            <div class="card-header bg-secondary text-light">Unidad Exterior</div>
		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">

            <thead>
            <tr>
                <th>#</th>  
                <th>Empresa</th>
                <th>Sucursal</th>
                <th>Sistema Onsite</th>
                <th>Terminales</th>              
                <th>Medida_1_a</th>
                <th>Medida_1_b</th>
                <th>Medida_1_c</th>
                <th>Medida_1_d</th>
                <th>Medida_2_a</th>
                <th>Medida_2_b</th>
                <th>Medida_2_c</th>
                <th>Tipo Anclaje</th>
                <th>Contra Sifón</th>
                <th>500mm_ultima_derivacion_curva</th>
            </tr>
            </thead>
            <tbody class="small">
            @foreach($terminalesUnidadExterior as $unidadExterior)
                <tr>                	
                    <td><a href={{ url('terminalOnsite/' . $unidadExterior->nro . '/editarUnidadExterior'  ) }}>{{$unidadExterior->nro}}</a></td>
                    <td>{{$unidadExterior->empresa}}</td>
                    <td>{{$unidadExterior->sucursal}}</td>
                    <td>{{$unidadExterior->sistemaonsite}}</td>
                    <td>
                        @if($unidadExterior->all_terminales_sucursal)
                            SI
                        @else
                            NO
                        @endif
                    </td>
                    <td>{{$unidadExterior->medida_figura_1_a}}</td>
                    <td>{{$unidadExterior->medida_figura_1_b}}</td>
                    <td>{{$unidadExterior->medida_figura_1_c}}</td>
                    <td>{{$unidadExterior->medida_figura_1_d}}</td>
                    <td>{{$unidadExterior->medida_figura_2_a}}</td>
                    <td>{{$unidadExterior->medida_figura_2_b}}</td>
                    <td>{{$unidadExterior->medida_figura_2_c}}</td>
                    <td>{{$unidadExterior->anclaje_piso}}</td>
                    <td>{{$unidadExterior->contra_sifon}}</td>
                    <td>{{$unidadExterior->mm_500_ultima_derivacion_curva}}</td>
                    
                    
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>Empresa</th>
                <th>Sucursal</th>
                <th>Sistema Onsite</th>
                <th>Terminales</th>
                <th>Medida_1_a</th>
                <th>Medida_1_b</th>
                <th>Medida_1_c</th>
                <th>Medida_1_d</th>
                <th>Medida_2_a</th>
                <th>Medida_2_b</th>
                <th>Medida_2_c</th>
                <th>Tipo Anclaje</th>
                <th>Contra Sifón</th>
                <th>500mm_ultima_derivacion_curva</th>
            </tr>
            </tfoot>
        </table>

		<!---- PAGINATE -->

		
		@include('pagination.default-limit-links', ['paginator' => $terminalesUnidadExterior, 'filters' => ''])
		

		<!----  -->

	</div>
</div>



@endsection
