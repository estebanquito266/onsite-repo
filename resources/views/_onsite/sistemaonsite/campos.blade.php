<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Datos Sistema Onsite</div>
	<div class="card-body">

		<!--label>Compania</label-->


		<input type="hidden" class="form-control" id="company_id" name="company_id" value="{{ (isset($company)) ? $company : '' }}">
		@if(isset($sistemaEditar))
			<input type="hidden" class="form-control" id="empresa_onsite_id" name="empresa_onsite_id" value="{{$sistemaEditar->empresa_onsite_id}}">
			<input type="hidden" class="form-control" id="sucursal_onsite_id" name="sucursal_onsite_id" value="{{$sistemaEditar->sucursal_onsite_id}}">
		@endif

		<div class=" form-group col-md-12 logica-sistema-empresa-modal">
			<label>Obra</label>
			<select name="obra_onsite_id" id="obra_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione obra'  {{ ((isset($sistemaEditar) && isset($sistemaEditar->obra_onsite_id))?'disabled':'') }}>
				<option value=""> -- Seleccione una --</option>
				@foreach ($obras as $obra)
				<option value="{{ $obra->id }}" {{ ((isset($sistemaEditar) && $obra->id == $sistemaEditar->obra_onsite_id)?'selected':'') }}>{{ $obra->nombre }}</option>
				@endforeach
			</select>
		</div>		
		<div class="form-group col-md-12 logica-sistema-sucursal-modal">
			<label>Sucursal</label>
			<select name="sucursal_onsite_id" id="sucursal_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione Sucursal onsite id' {{ ((isset($sistemaEditar))?'disabled':'') }}>
				@if(isset($sistemaEditar) && isset($sistemaEditar->sucursal_onsite))
				<option value="{{ $sistemaEditar->sucursal_onsite_id }}" selected="" >{{ $sistemaEditar->sucursal_onsite->razon_social }}</option>
				@endif
			</select>
		</div>

		<div class="form-group col-md-12">
			<label>Nombre</label>
			<input type="text" class="form-control" id="nombre" name="nombre" value="{{ (isset($sistemaEditar)) ? $sistemaEditar->nombre : '' }}">
		</div>

		<div class="form-group col-md-12">
			<label>Comentarios</label>
			<input type="textarea" class="form-control" id="comentarios" name="comentarios" value="{{ (isset($sistemaEditar)) ? $sistemaEditar->comentarios : '' }}">
		</div>
	</div>
</div>