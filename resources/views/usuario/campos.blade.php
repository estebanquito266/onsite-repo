<div class="row">



	<div class="form-group col-md-6">
		<label>Nombre</label>
		<input type="text" name="name" class="form-control" placeholder="Ingrese el nombre" value="{{(isset($usuario) ? $usuario->name : null )}}">
	</div>
	<div class="form-group col-md-6">
		<label>Email</label>
		@if(Request::segment(2)=='create')
		<input type="text" name="email" class="form-control" placeholder="Ingrese el email">
		@else
		<input type="text" name="email" class="form-control" placeholder="Ingrese el email" value="{{(isset($usuario) ? $usuario->email : null )}}" readonly>
		@endif
	</div>


	<div class="form-group col-md-6">
		<label>Password</label>

		<input type="password" name="password" class="form-control" placeholder="Ingrese el password">
	</div>
	<div class="form-group col-md-6">
		<label>Confirmar Password</label>

		<input type="password" name="confirmar_password" class="form-control" placeholder="Confirme el password">
	</div>

	<div class="form-group col-md-6">
		<label>Domicilio</label>
		<input type="text" name="domicilio" id="autocomplete" class="form-control" placeholder="Ingrese el domicilio" 
		value="{{(isset($usuario) ? $usuario->domicilio : null )}}">
	</div>
	<div class="form-group" id="latitudeArea">
            <label>Latitude</label>
            <input type="text" id="latitude" name="latitud" class="form-control" value="{{(isset($usuario) ? $usuario->latitud : null )}}">
        </div>
        <div class="form-group" id="longtitudeArea">
            <label>Longitude</label>
            <input type="text" name="longitud" id="longitude" class="form-control" value="{{(isset($usuario) ? $usuario->longitud : null )}}">
        </div>
		
	<div class="form-group col-md-6">
		<label>Cuit</label>
		<input type="cuit" name="cuit" class="form-control" placeholder="Ingrese la CUIT" value="{{(isset($usuario) ? $usuario->cuit : null )}}">

	</div>
	<div class="form-group col-md-6">
		<label>Teléfono</label>
		<input type="text" name="telefono" class="form-control" placeholder="Ingrese el teléfono" value="{{(isset($usuario) ? $usuario->telefono : null )}}">

		<p class="help-block">&nbsp;</p>
	</div>

	<div class="form-group col-md-6">
		<label>Foto de perfil</label>

		<br>
		@if( !(Request::segment(2)=='create') )
		@if( $usuario->foto_perfil )
		<a href="{!! URL::to( '/imagenes/'.$usuario->foto_perfil ) !!}" target="_BLANK">
			<img src="{!! URL::to( '/imagenes/'.$usuario->foto_perfil ) !!}" style="width:42px;">
		</a>
		@endif
		@endif

		<input name="foto_perfil" type="file" accept="image/x-png,image/gif,image/jpeg">
		<small class="form-text text-muted">
			Tamaño sugerido: 64x64 px (o proporcional: 128x128, 256x256...)
		</small>
	</div>

</div>