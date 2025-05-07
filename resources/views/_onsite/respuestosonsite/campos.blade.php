<style>
	.color_boton_imagen_izq {
		display: inline-block;
		width: 30px;
		height: 30px;
		background: #3f6ad8 no-repeat center center;
		background-size: 100% 100%;
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e");
		/* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e"); */
	}

	.color_boton_imagen_der {
		display: inline-block;
		width: 30px;
		height: 30px;
		background: #3f6ad8 no-repeat center center;
		background-size: 100% 100%;
		/* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3e%3c/svg%3e"); */
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23fff' viewBox='0 0 8 8'%3e%3cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3e%3c/svg%3e");
	}
</style>
<div class="main-card mb-3 card categoria_modelo">
	<div class="card-header bg-secondary text-white">
		Seleccione Categoría y Modelo
	</div>
	<div class="card-body">

		<div class="row">
			<div class="form-group col-12 col-md-6 selects_categorias">
				<label for="categoria_respuestos_id">Categoría</label>
				<select name="categoria_respuestos_id_X" id="categoria_respuestos_id" class="form-control multiselect-dropdown" placeholder='Seleccione categoría onsite id'>
					<option value="0"> -- Seleccione uno --</option>
					@foreach ($categoriasRespuestos as $categoriaRespuestos)
					<option value="{{ $categoriaRespuestos->id }}" {{ ((isset($detallePedido) && $detallePedido->categoria_respuestos_onsite_id == $categoriaRespuestos->id)?'selected':'') }}>{{ $categoriaRespuestos->nombre }}</option>
					@endforeach
				</select>
			</div>


			<div class="form-group col-12 col-md-6">
				<label for="modelo_respuestos_id">Modelo</label>
				<div class="row">
					<select name="modelo_respuestos_id_x" id="modelo_respuestos_id" class=" col-10 form-control multiselect-dropdown" placeholder='Seleccione empresa onsite id'>
					</select>
					<div class="input-group-append col-2">
						<button id="resetbutton" class="btn btn-secondary"><i class="pe-7s-refresh"> </i></button>
					</div>
				</div>
			</div>


		</div>


		<div class="row">

			<div class="form-group col-12 col-md-6">
				<label for="spare_parts_code">Código de Pieza</label>
				<div class="row">
					<input type="text" class="form-control col-8 ml-3" id="spare_parts_code">
					<div class="input-group-append col-2">
						<button id="spare_parts_code_filter" class="btn btn-secondary"><i class="pe-7s-refresh-2"> </i></button>
					</div>
				</div>
				<div class="row codigo-pieza-select d-none">
				    <div class="form-group col-12">
				        <label for="spare_parts_code"><br/>Códigos de Pieza encontrados</label>
     				    <select name="codigo-pieza-select" id="codigo-pieza-select" class=" col-8 form-control multiselect-dropdown" placeholder='Opciones de pieza encontradas'></select>
					</div>
				</div>
			</div>
		

			<div class="form-group col-12 col-md-6">
				<label for="part_name">Nombre de Pieza</label>
				<div class="row">
					<input type="text" class="form-control col-8 ml-3" id="part_name">
					<div class="input-group-append col-2">
						<button id="part_name_filter" class="btn btn-secondary"><i class="pe-7s-refresh-2"> </i></button>
					</div>
				</div>
			</div>


			<!-- <div class="form-group col-12 col-md-4">
				<label for="description">Description</label>
				<div class="row">
					<input type="text" class="form-control col-8 ml-3" id="description">
					<div class="input-group-append col-2">
						<button id="description_filter" class="btn btn-secondary"><i class="pe-7s-refresh-2"> </i></button>
					</div>
				</div>
			</div> -->

		</div>

	</div>

	<div>
		<!-- orden pedido -->
		<input type="hidden" id="orden_id" value="{{isset($idOrden) ? $idOrden : 0}}" />
		<input type="hidden" id="company_id" name="company_id" value="{{$company_id}}" />
		<input type="hidden" id="user_id" name="user_id" value="{{$user_id}}" />
		<input type="hidden" id="estado_id" name="estado_id" value="4" />

		<input type="hidden" id="monto_dolar" name="monto_dolar" value="0" />
		<input type="hidden" id="monto_euro" name="monto_euro" value="0" />
		<input type="hidden" id="monto_peso" name="monto_peso" value="0" />

		<input type="hidden" id="comentarios" name="comentarios" value="-" />
		<input type="hidden" id="empresa_onsite_id" name="empresa_onsite_id" value="{{isset($user->empresas_onsite[0])?$user->empresas_onsite[0]->id : ''}}">

		<!-- detalle orden pedido -->
		<input type="hidden" id="orden_respuestos_id" name="orden_respuestos_id" value="0" />


		<input type="hidden" id="pieza_respuestos_id" name="pieza_respuestos_id" value="0" />
		<input type="hidden" id="cantidad" name="cantidad" value="0" />
		<input type="hidden" id="precio_fob" name="precio_fob" value="0" />
		<input type="hidden" id="precio_total" name="precio_total" value="0" />
		<input type="hidden" id="precio_neto" name="precio_neto" value="0" />
		<input type="hidden" id="descuento_user" value="{{($user->descuento_respuestos_onsite > 0 ? $user->descuento_respuestos_onsite : 0)}}"></input>

	</div>


</div>



<div class="main-card mb-3 card imagen_despiece">
	<div class="card-header card-header-tab  ">
		<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
			<i class="header-icon pe-7s-tools mr-3 text-muted opacity-6"> </i>
			Despiece del Modelo
		</div>
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<button type="button" tabindex="0" class="dropdown-item" id="showtoast">
						<i class="dropdown-icon lnr-inbox"> </i><span>Menus</span>
					</button>
					<button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-file-empty"> </i><span id="pruebas">Settings</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		<h5 class="card-title"></h5>
		<div id="carouselExampleControls1" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" id="despiece_modelo">

				<!-- completa dinámicamente -->

			</div>

			<a class="carousel-control-prev" href="#carouselExampleControls1" role="button" data-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="sr-only">Anterior</span>
			</a>
			<a class="carousel-control-next" href="#carouselExampleControls1" role="button" data-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="sr-only">Siguiente</span>
			</a>
		</div>
	</div>
</div>


<div class="main-card mb-3 card listado_piezas ">

	<div class="card-header card-header-tab  ">
		<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
			<i class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i>
			Listado de Piezas
		</div>
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<!-- <button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-inbox"> </i><span>Menus</span>
					</button> -->
					<!-- <button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
					</button> -->
				</div>
			</div>
		</div>

	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="dtBasicExample" class="table table-striped table-bordered table_respuestos" cellspacing="0" width="100%">
			<!-- completa dinamico -->	
		</table>
		</div>
	</div>
</div>

<div class="main-card mb-3 card" id="detalle_pieza">

	<div class="card-header card-header-tab  ">
		<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
			<i class="header-icon pe-7s-plug mr-3 text-muted opacity-6"> </i>
			Detalle de Pieza
		</div>
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<button type="button" tabindex="0" class="dropdown-item" id="hide_detalle">
						<i class="dropdown-icon lnr-inbox"> </i><span>Ocultar</span>
					</button>
					<button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-file-empty"> </i><span>-</span>
					</button>
				</div>
			</div>
		</div>

	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-6 card">
				<!-- <div id="part_image_div"></div> -->
				<div class="widget-heading">Modelos</div>
				<div class="widget-subheading" id="modelo_respuestos_onsite_id_div"></div>
			</div>

			<div class="col-6">
				<div class="widget-content p-0">
					<div class="widget-content-wrapper row">

						<!-- <div class="widget-content-left col-6">
							<div class="widget-heading">Modelos</div>
							<div class="widget-subheading" id="modelo_respuestos_onsite_id_div"></div>
						</div> -->

						<div class="widget-content-left col-6">
							<div class="widget-heading">Numero</div>
							<div class="widget-subheading" id="numero_div"></div>
						</div>

						<div class="widget-content-left col-6">
							<div class="widget-heading">Spare Part</div>
							<div class="widget-subheading" id="spare_parts_code_div"></div>
						</div>

						<div class="widget-content-left col-6">
							<div class="widget-heading">Part Name</div>
							<div class="widget-subheading" id="part_name_div"></div>
						</div>

						<div class="widget-content-left col-6">
							<div class="widget-heading">Precio de venta sugerido</div>
							<div class="widget-subheading" id="precio_fob_div"></div>
						</div>
						<div class="widget-content-left col-6">
							<div class="widget-heading">Peso</div>
							<div class="widget-subheading" id="peso_div"></div>
						</div>
						<div class="widget-content-left col-6">
							<div class="widget-heading">Dimensiones</div>
							<div class="widget-subheading" id="dimensiones_div"></div>
						</div>
						<div class="widget-content-left col-6">
							<div class="widget-heading">PIA</div>
							<div class="widget-subheading" id="pia_div"></div>
						</div>

						<div class="widget-content-left col-12">
							<div class="widget-heading">Description</div>
							<div class="widget-subheading" id="description_div"></div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="main-card mb-3 card" id="carrito_repuestos">
	<div class="card-header card-header-tab  ">
		<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
			<i class="header-icon pe-7s-cart mr-3 text-muted opacity-6"> </i>
			Repuestos en carrito
		</div>
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<button type="button" tabindex="0" class="dropdown-item" id="hide_detalle">
						<i class="dropdown-icon lnr-inbox"> </i><span>Ocultar</span>
					</button>
					<button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
					</button>
				</div>
			</div>
		</div>

	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="dtFooterRespuestos" class="table table-striped table-bordered table_respuestos" cellspacing="0" width="100%">
			</table>
		</div>
	</div>
</div>


<div class="mb-3 card">
	<div class="p-0 card-body">
		<ul class="list-group list-group-flush">
			<li class="p-3 bg-transparent list-group-item">
				<div class="widget-content p-0">
					<div class="widget-content-outer">
						<div class="widget-content-wrapper row">
							<div class="widget-content-left col-lg-3 col-4">
								<div class="widget-heading">Subtotal Pedido</div>
								<!-- <div class="widget-heading">Descuento Usuario {{$user->name}} : {{$user->perfil_usuario[0]->perfil->descuento_respuestos_onsite}}% </div> -->
								<div class="widget-heading">Descuento: {{$user->perfil_usuario[0]->perfil->descuento_respuestos_onsite}}% </div>
								<div class="widget-subheading">Monto total del pedido de respuestos</div>
							</div>

							<div class="widget-content-right col-lg-3 col-8">
								<div class="text-success  text-right">
									Total en Pesos
								</div>
								<div class="text-warning  text-right total_carrito_peso">
									$0,00
								</div>
								<div class=" text-danger  text-right descuento_user_peso">
									$0,00
								</div>
								<div class="widget-numbers text-right text-success monto_final_peso">
									$0,00
								</div>
								<br>
							</div>
							<div class="widget-content-right col-lg-3 col-8">
								<div class="text-success  text-right">
									Total en Euro
								</div>
								<div class="text-warning  text-right total_carrito_euro">
									$0,00
								</div>
								<div class=" text-danger  text-right descuento_user_euro">
									$0,00
								</div>
								<div class="widget-numbers text-right text-success monto_final_euro">
									$0,00
								</div>
								<br>
							</div>

							<div class="widget-content-right col-lg-3 col-8">
								<div class="text-success  text-right">
									Total en Dólar
								</div>
								<div class="text-warning  text-right total_carrito_dolar">
									$0,00
								</div>
								<div class=" text-danger  text-right descuento_user_dolar">
									$0,00
								</div>
								<div class="widget-numbers text-right text-success monto_final_dolar">
									$0,00
								</div>
								<br>
							</div>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>