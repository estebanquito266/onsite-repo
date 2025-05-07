@extends('layouts.baseprolist')

@section('content')
					
					
    @include('_onsite.reparaciononsite.top')  

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Tablas Soporte de Reparaciones Onsite</h3> 
                
        </div>
        <div class="card-body">
            <div class="form-row mt-3">	
                <div  class="col-md-6 col-lg-6" >
                    <h3>Empresas Onsite</h3>
                    <table class="table table-striped" id="tablaEmpresa">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empresa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empresas as $empresa)
                            <tr>
                                <td>{{$empresa->id}}</td>								
                                <td>{{$empresa->nombre}}</td>							
                            </tr>
                            @endforeach														
                        </tbody>
                    </table>
                </div>			
            
                <div  class="col-md-6 col-lg-6" >
                    <h3>Estados Onsite</h3>
                    <table class="table table-striped" id="tabla">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estados as $estado)
                            <tr>
                                <td>{{$estado->id}}</td>								
                                <td>{{$estado->nombre}}</td>							
                            </tr>
                            @endforeach														
                        </tbody>
                    </table>
                </div>
                
                
                <div  class="col-md-6 col-lg-6" >
                    <h3>Tipos de Servicios Onsite</h3>
                    <table class="table table-striped" id="tabla2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo de Servicio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tiposServicios as $tipoServicio)
                            <tr>
                                <td>{{$tipoServicio->id}}</td>								
                                <td>{{$tipoServicio->nombre}}</td>							
                            </tr>
                            @endforeach														
                        </tbody>
                    </table>
                </div>
                
                
                <div  class="col-md-6 col-lg-6" >
                    <h3>Niveles Onsite</h3>
                    <table class="table table-striped" id="tabla3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($niveles as $nivel)
                            <tr>
                                <td>{{$nivel->id}}</td>								
                                <td>{{$nivel->nombre}}</td>							
                            </tr>
                            @endforeach														
                        </tbody>
                    </table>
                </div>					

                
                <div  class="col-md-6 col-lg-6" >
                    <h3>Provincias</h3>
                    <table class="table table-striped" id="tabla3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Provincia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($provincias as $provincia)
                            <tr>
                                <td>{{$provincia->id}}</td>								
                                <td>{{$provincia->nombre}}</td>							
                            </tr>
                            @endforeach														
                        </tbody>
                    </table>
                </div>														
            </div>														
            
        </div>
    </div>		
					
					
@endsection