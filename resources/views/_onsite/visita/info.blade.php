<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">INFORMACIÓN ADICIONAL</div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
				<label>Usuario creador de la visita</label>
				<input type="text" readonly class="form-control" value="[{{$reparacionOnsite->user->id}}] {{$reparacionOnsite->user->name}}">
			</div>
        </div>
    </div>
</div>