@if( Request::segment(3)=='edit' )
	<div class="main-card mb-3 card ">
		<div class="card-header bg-secondary text-white">Técnicos Onsite</div>
		<div class="card-body">
			<div class="form-row mt-3">
				<div class="form-group col-lg-12 col-md-12">
					<select class="estados-select form-control multiselect-dropdown" id="estados" name="user_tecnico_id[]" multiple data-live-search="true">
						@foreach ($tecnicosOnsite as $id => $nombre)
							<option value="{{ $id }}" {{ ((isset($tecnicosOnsiteSeleccionados) && in_array($id, $tecnicosOnsiteSeleccionados)) ? 'selected' : '')}}>{{ $nombre }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
	</div>
@endif