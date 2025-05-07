<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-white">
		Solicitud
	</div>
	<div class="card-body">
		<div class="form-row mt-3">
		@if(Request::segment(2)=='create' || Request::segment(3)=='edit' || Request::segment(1)=='reparacion')           
			<div class="form-group col-lg-6 col-md-6">            
				<label>Obra</label>
				<select name="obra_onsite_id" id="obra_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione la obra'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($obrasOnsite as $obra)
						<option value="{{ $obra->id }}"  {{ ((isset($solicitudOnsite) && $obra->id == $solicitudOnsite->obra_onsite_id)?'selected':'') }} >{{$obra->nombre}}</option> 
					@endforeach
				</select>
			</div>             
					
			<div class="form-group col-lg-6 col-md-6">
				<label>Estado Solicitud Onsite {{(Request::segment(3)=='edit' ? ' Actual' : null)}}</label>
				<div class="form-group input-group ">
					<select name="estado_solicitud_onsite_id" id="estado_solicitud_onsite_id" class="form-control mr-1" 
					
					{{(Request::segment(3)=='edit' ? 'readonly disabled' : null)}}
					>
						<option value=""> -- Seleccione uno --</option>  
						@foreach($estadosSolicitudOnsite as $estado) 
							<option value="{{$estado->id}}" {{ ((isset($solicitudOnsite) && $estado->id == $solicitudOnsite->estado_solicitud_onsite_id)?'selected':'') }}>{{$estado->nombre}}</option>
						@endforeach
					</select>
				
				</div>
			</div>
        @else
			<div class="form-group col-lg-6 col-md-6">
				<label>Obra Onsite Id</label>				
				<input type="text" name="obra_onsite_id" id="obra_onsite_id" value="{{ ( isset($solicitudOnsite)?$solicitudOnsite->obra_onsite_id:'') }}" class="form-control" readonly="true">				
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Estado Solicitud</label>			
				<input type="text" name="estado_solicitud_onsite" id="estado_solicitud_onsite" value="{{ (isset($solicitudOnsite)?$solicitudOnsite->estado_solicitud_onsite->nombre:'')}}" class="form-control" readonly="true">				
			</div>
        @endif

		
			<div class="form-group  col-lg-6 col-md-6">
				<label for="solicitud_tipo_id">Tipo de Solicitud</label>
				<select class="form-control multiselect-dropdown" name="solicitud_tipo_id" id="solicitud_tipo_id" {{(Request::segment(3)!='edit' ? 'readonly disabled' : null)}}>
					@foreach ($solicitudesTipos as $tipo)
					<option value="{{$tipo->id}}" {{ ((isset($solicitudOnsite) && $solicitudOnsite->solicitud_tipo_id == $tipo->id) ? 'selected' : '') }} >{{$tipo->nombre}}</option>
					@endforeach
				</select>
			</div>			

			<div class="form-group col-12">
				<label>Observaciones Internas</label>
				<textarea rows="5" name='observaciones_internas' id="observaciones_internas" class='form-control'> {{ (isset($solicitudOnsite)?$solicitudOnsite->observaciones_internas:null) }} </textarea>

			</div>
			<div class="form-group col-12 col-md-9">
				<label>Nota Obra</label>

				<input type="text" name='nota_cliente' id="nota_cliente" class='form-control' placeholder='Ingrese nota cliente' value="{{ (isset($solicitudOnsite)?$solicitudOnsite->nota_cliente:null) }}">

			</div>

			

			<div class="form-group col-md-3">
				<label>Fecha y hora de Creación</label>
				<input type="text" name='created_at' class='form-control' value="{{ (isset($solicitudOnsite)?date( 'd-m-Y H:i:s', strtotime($solicitudOnsite->created_at)): date('d-m-Y H:i:s')) }}" readonly>
			</div>
			

			
			

		</div>

	</div>
</div>