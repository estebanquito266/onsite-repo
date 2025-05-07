@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.sistemaonsite.top')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Sistema Onsite ID #{{$sistemaShow->id}}</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('sistemaOnsite') }}" id="sistemaOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="main-card mb-3 card ">
            <div class="card-header bg-secondary text-light">Datos Sistema</div>
            <div class="card-body">
                <div class="form-row mt-3">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Empresa</label>
                        <input type="text" name='empresa_onsite_id' id="empresa_onsite_id" class='form-control' value="{{ $sistemaShow->empresa_onsite->nombre}}" disabled>
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Sucursal</label>
                        <input type="text" name='sucursal_onsite_id' id="sucursal_onsite_id" class='form-control'  value="{{$sistemaShow->sucursal_onsite->codigo_sucursal}}" disabled>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Nombre</label>
                        <input type="text" name='nombre' id="nombre" class='form-control'  value="{{$sistemaShow->nombre}}" disabled>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Comentario</label>
                        <textarea type="text" name='comentarios' id="comentarios" class='form-control' disabled>{{$sistemaShow->comentarios}}</textarea>
                    </div>


                </div>

            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-header bg-secondary text-light">Unidad Interior</div>                

            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>                    
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Domicilio</th>
                </tr>
                </thead>
                <tbody class="small">
                @if($terminalesUnidadInterior)
                    @foreach($terminalesUnidadInterior as $unidadInterior)
                        <tr>
                            <td>{{$unidadInterior->nro}}</td>                            
                            <td>{{$unidadInterior->modelo}}</td>
                            <td>{{$unidadInterior->serie}}</td>
                            <td>{{$unidadInterior->domicilio}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>

                <tfoot>
                <tr>
                    <th>#</th>
                    <th>Modelo</th>
                    <th>Serie</th>
                    <th>Domicilio</th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-header bg-secondary text-light">Unidad Exterior</div>

            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
            <thead>
            <tr>
                <th>#</th>  
                <th>Terminales</th>              
                <th>Medida_1_a</th>
                <th>Medida_1_b</th>
                <th>Medida_1_c</th>
                <th>Medida_1_d</th>
                <th>Medida_2_a</th>
                <th>Medida_2_b</th>
                <th>Medida_2_c</th>
                <th>Tipo Anclaje</th>
                <th>Contra Sifón</th>
                <th>500mm_ultima_derivacion_curva</th>
            </tr>
            </thead>
            <tbody class="small">
            @if($terminalesUnidadExterior)
                @foreach($terminalesUnidadExterior as $unidadExterior)
                    <tr>
                        <td>{{$unidadExterior->nro}}</td>
                        <td>
                            @if($unidadExterior->all_terminales_sucursal)
                                SI
                            @else
                                NO
                            @endif
                        </td>
                        <td>{{$unidadExterior->medida_figura_1_a}}</td>
                        <td>{{$unidadExterior->medida_figura_1_b}}</td>
                        <td>{{$unidadExterior->medida_figura_1_c}}</td>
                        <td>{{$unidadExterior->medida_figura_1_d}}</td>
                        <td>{{$unidadExterior->medida_figura_2_a}}</td>
                        <td>{{$unidadExterior->medida_figura_2_b}}</td>
                        <td>{{$unidadExterior->medida_figura_2_c}}</td>
                        <td>{{$unidadExterior->anclaje_piso}}</td>
                        <td>{{$unidadExterior->contra_sifon}}</td>
                        <td>{{$unidadExterior->mm_500_ultima_derivacion_curva}}</td>
                        
                        
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th>#</th>
                <th>Terminales</th>
                <th>Medida_1_a</th>
                <th>Medida_1_b</th>
                <th>Medida_1_c</th>
                <th>Medida_1_d</th>
                <th>Medida_2_a</th>
                <th>Medida_2_b</th>
                <th>Medida_2_c</th>
                <th>Tipo Anclaje</th>
                <th>Contra Sifón</th>
                <th>500mm_ultima_derivacion_curva</th>
            </tr>
            </tfoot>
        </table>
        </div>
    </form>

@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/sistemas-onsite.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sucursales.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
@endsection
