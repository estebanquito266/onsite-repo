@extends('layouts.baseprocrud')

@section('content')


<div class="app-page-title mt-1">
	<div class="page-title-wrapper">
		<div class="page-title-heading col-md-6">
			<div class="page-title-icon">
				<i class="pe-7s-tools icon-gradient bg-tempting-azure"></i>
			</div>
			<div>
				REPARACIONES
				<div class="page-title-subheading">Importación de Reparaciones</div>
			</div>
		</div>
	</div>
</div>



<form method="POST" action="{{ url('importarReparacionesOnsite') }}" id="importReparaciones" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}

	<div id="cargando_bgh"></div>
	<link rel="stylesheet" href="/assets/css/dragdropfiles.css" type="text/css">
	<div class="main-card mb-3 card formulario_obra" hidden>
		<div class="card-body">
			<div id="smartwizard" class="sw-main sw-theme-default">
				<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">
					<div id="step-1" class="tab-pane step-content " style="display: block;">
						<div class="main-card card imagen_despiece">
							<div class="card-header card-header-tab  ">
								<div class="card-header-title font-size-lg font-weight-normal">
									<i class="header-icon pe-7s-tools mr-3 text-muted opacity-6"> </i>
									Carga de reparaciones
								</div>

								<div class="btn-actions-pane-right actions-icon-btn">
									<div class="btn-group dropdown">
										<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
											<i class="pe-7s-menu btn-icon-wrapper"></i>
										</button>
										<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
											<a href="{{'/imports/template_reparaciones_onsite_2024.xlsx'}}" type="button" tabindex="0" class="dropdown-item" id="showtoast">
												<i class="dropdown-icon lnr-inbox"> </i><span>Descargar Modelo</span>
											</a>
										</div>
									</div>
								</div>
							</div>

							<div class="card-body">
								<div class="mt-3 mb-3">
								<p data-renderer-start-pos="5468"><br><strong data-renderer-mark="true"><u data-renderer-mark="true">Insertar reparaciones</u></strong><br>	Para insertar una nueva reparación se requieren los siguientes datos como mínimo obligatorio:<br>	Clave<br>	Empresa<br>	Sucursal<br>	Terminal<br>	Tipo Servicio<br><strong data-renderer-mark="true"><u data-renderer-mark="true">Actualizar reparaciones:</u></strong><br>	Sólo es posible actualizar los siguientes campos:<br>	id_empresa_onsite<br>	sucursal_onsite_id<br>	id_terminal<br>	tarea<br>	tarea_detalle<br>	id_tipo_servicio<br>	id_estado<br>	fecha_ingreso<br>	observacion_ubicacion<br>	fecha_coordinada<br>	fecha_vencimiento<br>	fecha_cerrado<br>	sla_status<br>	sla_justificado<br>	monto<br>	monto_extra<br>	liquidado_proveedor<br>	visible_cliente<br>	chequeado_cliente<br>	problema_resuelto<br>	usuario_id<br>	nota_cliente<br>	observaciones_internas </p>

								<p data-renderer-start-pos="6224"><strong data-renderer-mark="true"><u data-renderer-mark="true">Importante:</u></strong><br>	Para todas las importaciones que realice, cada Sucursal y Localidad debe tener un técnico asignado.<br></p>
								</div>
								<div id="carouselExampleControls1" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner" id="despiece_modelo">

										<div class="form-row mt-3 empresa_obra">
											<div class="card-body row form-group px-2" id="datos_obra_div">
												<div class="form-group col-12">
													<small>Seleccione el archivo de su ordenador y luego click en Procesar Archivo para cargar las reparaciones.</small>
													<div class="file-drop-area card mt-2 ml-2 mr-2">
														<span class="choose-file-button">Seleccionar archivos</span>
														<span class="file-message mt-2">o arrastrar archivos aquí</span>
														<input multiple type="file" name='file' id="file" class="file-input" placeholder="Seleccione el archivo" value="{{ (isset($obraOnsite)?$obraOnsite->esquema:null) }}" required>
													</div>
												</div>
											</div>
										</div>

										<div class="divider"></div>


										<div class="card-footer modal-footer">
											<button type="button" id="import_file" class="btn btn-primary  pull-right mt-1 mr-4" style="width: 10%;">Procesar</button>
											<a type="button" href="/reparacionOnsite" class="btn btn-danger  pull-right mt-1" style="width: 10%;">Volver</a>

										</div>



									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


</form>

@endsection

@section('modals')

@include('_onsite.reparaciononsite.imports.modalConfirmacion')


@endsection

@section('scripts')

<script type="text/javascript" src="{!! asset('/assets/js/_reparacionOnsiteImports/getEvents.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_reparacionOnsiteImports/functions.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_reparacionOnsiteImports/getQuerys.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_reparacionOnsiteImports/postQuery.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_reparacionOnsiteImports/form-wizard-import.js') !!}"></script>


<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrK_SmK6NrXU72tphpRZKccnEIPnSieR8&libraries=places&v=weekly" defer></script>

<script type="text/javascript" src="{!! asset('/assets/js/librerias/googleMaps.js') !!}"></script>


<style>
	.card-header,
	.card-title {
		text-transform: unset !important;
	}
</style>
@endsection