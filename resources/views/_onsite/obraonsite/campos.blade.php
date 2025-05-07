<div class="main-card mb-3 card ">
	<div class="card-header text-white bg-secondary"> Obra </div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group  col-md-6">
				<label>Nombre</label>

				<input type="text" name='nombre' id="nombre" class='form-control' placeholder='Ingrese nombre' value="{{ (isset($obraOnsite)?$obraOnsite->nombre:null) }}">

			</div>
			<div class="form-group  col-md-6">
				<label>Nro Cliente BGH Ecosmart</label>

				<input type="text" name='nro_cliente_bgh_ecosmart' id="nro_cliente_bgh_ecosmart" class='form-control' placeholder='Ingrese nro_cliente_bgh_ecosmart' value="{{ (isset($obraOnsite)?$obraOnsite->nro_cliente_bgh_ecosmart:null) }}">

			</div>
			<div class="form-group col-6">
				<label>Domicilio</label>
				<input type="text" name='domicilio' id="autocomplete" class='form-control' placeholder='Ingrese domicilio' 
				value="{{ (isset($obraOnsite)?$obraOnsite->domicilio:null) }}">
			</div>
			<div class="form-group col-2" id="latitudeArea">
				<label>Latitud</label>
				<input type="text" id="latitude" name="latitud" class="form-control"
				value="{{ (isset($obraOnsite)?$obraOnsite->latitud:null) }}">
			</div>
			<div class="form-group col-2" id="longtitudeArea">
				<label>Longitud</label>
				<input type="text" name="longitud" id="longitude" class="form-control"
				value="{{ (isset($obraOnsite)?$obraOnsite->longitud:null) }}">
			</div>

			<div class="form-group col-2 ">
				<label>
					Obra VIP
				</label>
				<br>
				<input type="checkbox" id="vip" name="vip" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->vip) ? 'checked' : '' }}>


			</div>

			<div class="form-group col-lg-6 col-md-6" hidden>
				<label>Unidades Exteriores</label>
				<input type="number" name='cantidad_unidades_exteriores' class='form-control' placeholder='Ingrese cantidad_unidades_exteriores' value="{{ (isset($obraOnsite)?$obraOnsite->cantidad_unidades_exteriores:null) }}">
			</div>
			<div class="form-group col-lg-6 col-md-6" hidden>
				<label>Unidades Interiores</label>
				<input type="number" name='cantidad_unidades_interiores' class='form-control' placeholder='Ingrese cantidad_unidades_interiores' value="{{ (isset($obraOnsite)?$obraOnsite->cantidad_unidades_interiores:null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6" hidden>
				<label>Avance de obra (%)</label>
				<input type="number" name='estado' class='form-control' placeholder='Ingrese avance de obra (%)' value="{{ (isset($obraOnsite)?$obraOnsite->estado:null) }}">
			</div>
			<div class="form-group col-lg-12 col-md-12">
				<label>Detalle del estado</label>

				<input type="text" name='estado_detalle' id="estado_detalle" class='form-control' placeholder='Ingrese estado_detalle' value="{{ (isset($obraOnsite)?$obraOnsite->estado_detalle:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6" hidden>
				<label>Esquema</label>
				<input type="file" name='esquema_archivo' id="esquema_archivo" class="d-block" placeholder="Seleccione el archivo" value="{{ (isset($obraOnsite)?$obraOnsite->esquema:null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6">

				@if(isset($obraOnsite) && $obraOnsite->esquema)
				<label>Esquema</label>
				<br>
				<a class="mt-5 pt-5" href="/imagenes/reparaciones_onsite/{{$obraOnsite->esquema}}" target="_blank">
					@if( in_array( strtolower(pathinfo($obraOnsite->esquema, PATHINFO_EXTENSION)) , ['gif', 'jpg', 'jpeg', 'png'] ) )
					<img src="/imagenes/reparaciones_onsite/{{$obraOnsite->esquema}}" width=100>
					@else
					Link Esquema
					@endif
				</a>
				@endif
			</div>
		</div>

	</div>
</div>

<div class="main-card mb-3 card ">
	<div class="card-header text-white bg-secondary"> Obra - Empresa Instaladora</div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group col-lg-6 col-md-6">
				<label>Nombre de la Empresa</label>

				<input type="text" name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' placeholder='Ingrese empresa_instaladora_nombre' value="{{ (isset($obraOnsite)?$obraOnsite->empresa_instaladora_nombre:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Responsable</label>

				<input type="text" name='empresa_instaladora_responsable' id="empresa_instaladora_responsable" class='form-control' placeholder='Ingrese empresa_instaladora_responsable' value="{{ (isset($obraOnsite)?$obraOnsite->empresa_instaladora_responsable:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Email de Responsable</label>

				<input type="email" name='responsable_email' id="responsable_email" class='form-control' placeholder='Ingrese responsable_email' value="{{ (isset($obraOnsite)?$obraOnsite->responsable_email:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Teléfono de Responsable</label>
				<input type="number" name='responsable_telefono' class='form-control' placeholder='Ingrese responsable_telefono' value="{{ (isset($obraOnsite)?$obraOnsite->responsable_telefono:null) }}">
			</div>


		</div>

	</div>
</div>

<div class="main-card mb-3 card ">
	<div class="card-header text-white bg-secondary"> Obra - Requisitos de acceso</div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="row form-group col-lg-12 col-md-12 pt-1 mt-1 border-top">
				<div class="form-check col-lg-6 col-md-6 mt-2">

					<label class="form-check-label" for="requiere_zapatos_seguridad">
						Requiere zapatos de seguridad
					</label>
					<input type="checkbox" id="requiere_zapatos_seguridad" name="requiere_zapatos_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_zapatos_seguridad) ? 'checked' : '' }}>
				</div>
				<div class="form-check col-lg-6 col-md-6  mt-2">

					<label class="form-check-label" for="requiere_casco_seguridad">
						Requiere cascos de seguridad
					</label>
					<input type="checkbox" id="requiere_casco_seguridad" name="requiere_casco_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_casco_seguridad) ? 'checked' : '' }}>
				</div>
				<div class="form-check col-lg-6 col-md-6 mt-2">

					<label class="form-check-label" for="requiere_proteccion_visual">
						Requiere protección visual
					</label>
					<input type="checkbox" id="requiere_proteccion_visual" name="requiere_proteccion_visual" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_visual) ? 'checked' : '' }}>
				</div>
				<div class="form-check col-lg-6 col-md-6 mt-2">

					<label class="form-check-label" for="requiere_proteccion_auditiva">
						Requiere protección auditiva
					</label>
					<input type="checkbox" id="requiere_proteccion_auditiva" name="requiere_proteccion_auditiva" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_auditiva) ? 'checked' : '' }}>
				</div>
				<div class="form-check col-lg-6 col-md-6 mt-2">

					<label class="form-check-label" for="requiere_art">
						Requiere Art.
					</label>
					<input type="checkbox" id="requiere_art" name="requiere_art" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_art) ? 'checked' : '' }}>
				</div>
				<div class="form-check col-lg-6 col-md-6 mt-2">

					<label class="form-check-label" for="clausula_no_arrepentimiento">
						Cláusula de No Repetición
					</label>
					<input type="checkbox" id="clausula_no_arrepentimiento" name="clausula_no_arrepentimiento" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->clausula_no_arrepentimiento) ? 'checked' : '' }}>
				</div>
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>CUIT</label>
				<input type="number" name='cuit' class='form-control' placeholder='Ingrese cuit' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cuit:null) }}">
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Razón Social</label>
				<input type="text" name='razon_social' class='form-control' placeholder='Ingrese razón social' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->razon_social:null) }}">
			</div>
			<div class="form-group col-lg-12 col-md-12">
				<label>CNR Detalle</label>
				<input type="text" name='cnr_detalle' class='form-control' placeholder='Ingrese detalle' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cnr_detalle:null) }}">
			</div>

		</div>

	</div>
</div>