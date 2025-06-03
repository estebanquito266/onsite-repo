<div class="modal fade" id="modalCrearTicket" tabindex="-1" role="dialog" aria-labelledby="modalLabelCrearTicket">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelEditarTicket" style="display: none; font-weight: bold;"><a href="#" id="urlTicketEdit" class="search-button">
                    <i class="fas fa-search search-icon"> </i>Ticket
                </a> - Añadir Comentario</h5>

                <h5 class="modal-title" id="modalLabelAgregarTicket" style="font-weight: bold;">Ticket - Nuevo</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
               
            </div>
            <div class="main-card mb-3 card" id="listadoTickets">
                <div class="card-header bg-secondary"></div>
                <div class="card-body">
                    <div class="form-row mt-3">
                        <div class="form-group col-lg-12 col-md-12">
                        <select name="tickets_list" id="tickets_list" class="form-control">
                            <option value=""></option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
            {!!Form::open(['route'=>'ticket.store', 'method'=>'POST', 'files'=>true, 'id'=>'ticketForm'])!!}            

            @include('tickets.modal-campos')
            
            <div class="modal-footer" id="ticket_form_button">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
                <button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
            </div>            

            {!!Form::close()!!}
            
            @section('scripts')	
                {!!Html::script('/assets/js/tickets/buscar.js')!!}
            @endsection
            <div id="div_comment_section">
                @include('tickets.comment.campos')
            </div>
           
		</div>
	</div>
</div>

