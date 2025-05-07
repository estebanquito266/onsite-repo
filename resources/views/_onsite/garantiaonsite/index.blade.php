@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.garantiaonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">BGH Eco Smart - Garantías</h3>
	</div>



	<div class="card-body">
		<h4 class="card-title"></h4>
		<p class="card-text"></p>

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
			<thead>
				<tr>
					<th>id</th>
					<th>Garantía</th>
					<th>Empresa Instaladora</th>
					<th>Obra</th>
					<th>Sistema</th>
					<th>Comprador</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($garantias as $garantia)
				<tr>
					<td scope="row">{{$garantia->id}}</td>
					<td>{{$garantia->nombre}}</td>
					<td>{{isset($garantia->empresa_instaladora)? $garantia->empresa_instaladora->nombre:null }}</td>
					<td>{{$garantia->obra_onsite->nombre}}</td>
					<td>{{isset($garantia->sistema_onsite) ? $garantia->sistema_onsite->nombre: null }}</td>
					<td>{{$garantia->sistema_onsite->comprador_onsite->nombre}}</td>
					<td>
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">

									<a href="/garantiaOnsiteEmitir/{{$garantia->id}}" target="_blank" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note2"> </i><span>Emitir Garantía</span></a>

									@if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
									<a href="/garantiaonsite/{{$garantia->id}}/edit" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note"> </i><span>Editar</span></a>
									@endif

									<a href="#" data-garantia_id="{{$garantia->id}}" data-garantia_nombre="{{$garantia->nombre}}" type="button" tabindex="0" class="dropdown-item eliminar_garantia_btn"><i class="dropdown-icon pe-7s-trash"> </i><span>Eliminar</span></a>									

								</div>
							</div>
						</div>
					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
		<input type="hidden" id="garantia-delete-link" />
	</div>

</div>
@endsection


@section('modals')
@include('modal.modalConfirmacion')
@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/garantias.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/solicitudes/getEvents.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
<script src="/assets/js/scripts-init/form-components/form-wizard.js"></script>
@endsection