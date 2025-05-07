
@extends('layouts.baseprolist')

@section('content')

    @include('_onsite.visita.top')  

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Reporte de Visitas</h3> 
        </div>
        <div class="card-body">
            <center>
                <div class="form-group">
                    <a href="exports/listado_reparaciononsite_extendido_{{ $user_id }}.xlsx" class="btn btn-success" style="display:inline;" >Descargar Reporte de Visitas</a>
                </div>		
            </center>							
        </div>
    </div>
					
@endsection

