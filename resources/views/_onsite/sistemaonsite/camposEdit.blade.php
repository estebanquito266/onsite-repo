
	 <div class="main-card mb-3 card">
            <div class="card-header bg-secondary text-light">Unidad Interior</div>
            <div>
                <a class="btn-shadow mr-3 btn btn-alternate" style="margin-left: 1em; margin-top: 1em; margin-bottom: 1em"  href={{ url('terminalOnsite/' . $sistemaEditar->id . '/crearUnidadInterior'  ) }} class="btn btn-primary btn-pill mt-2">Crear</a>
                
            </div>


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
                @foreach($terminalesUnidadInterior as $unidadInterior)
                    <tr>
                        <td><a href={{ url('terminalOnsite/' . $unidadInterior->nro . '/editarUnidadInterior'  ) }}>{{$unidadInterior->nro}}</a></td>                            
                        <td>{{$unidadInterior->modelo}}</td>
                        <td>{{$unidadInterior->serie}}</td>
                        <td>{{$unidadInterior->domicilio}}</td>                        
                    </tr>

                @endforeach
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
            <div>
                <a class="btn-shadow mr-3 btn btn-alternate" style="margin-left: 1em; margin-top: 1em; margin-bottom: 1em"  href={{ url('terminalOnsite/' . $sistemaEditar->id . '/crearUnidadExterior'  ) }} class="btn btn-primary btn-pill mt-2">Crear</a>                
            </div>
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
            @foreach($terminalesUnidadExterior as $unidadExterior)
                <tr>
                    <td><a href={{ url('terminalOnsite/' . $unidadExterior->nro . '/editarUnidadExterior'  ) }}>{{$unidadExterior->nro}}</a></td>
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
                    <td>{{$unidadExterior->medida_figura_2_a}}</td>
                    <td>{{$unidadExterior->medida_figura_2_b}}</td>
                    <td>{{$unidadExterior->medida_figura_2_c}}</td>
                    <td>{{$unidadExterior->anclaje_piso}}</td>
                    <td>{{$unidadExterior->contra_sifon}}</td>
                    <td>{{$unidadExterior->mm_500_ultima_derivacion_curva}}</td>
                    
                    
                </tr>
            @endforeach
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

