<?php $isEditable = isset($viewMode) && $viewMode == 'edit'; ?>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Garantias</div>

    </div>
    <div class="card-body row">
        <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
					<th>Garantía</th>
					<th>Empresa Instaladora</th>
					<th>Sistema</th>
					<th>Comprador</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($garantias as $garantia)
                <tr>
                    <td>
                        <?php if($isEditable) :  ?>
                            <a href="{{ url('garantiaonsite/' . $garantia->id . '/edit') }}">{{$garantia->id}}</a>
                        <?php else:  ?>
                            {{$garantia->id}}
                        <?php endif;  ?>                           
                        
                    </td>
					<td>{{$garantia->nombre}}</td>
					<td>{{isset($garantia->empresa_instaladora)? $garantia->empresa_instaladora->nombre:null }}</td>
					<td>{{isset($garantia->sistema_onsite) ? $garantia->sistema_onsite->nombre: null }}</td>
					<td>{{$garantia->sistema_onsite->comprador_onsite->nombre}}</td>                                 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>