<html>

<head>
    <style>
        .pie_pagina {
            background-color: #f2f2f2;
            text-align: center;

        }

        .header_page {

            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            color: dimgray;
            font-style: oblique;
        }

        .centrado {
            text-align: center;
        }

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .customers td,
        .customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #354a43;
            color: white;
        }
    </style>
</head>

<body>


    <div class="customers">
        <br>
        <p><img style="display: block; margin-left: auto; margin-right: auto;" src="https://onsite.speedup.com.ar/imagenes/logo_local_BGH_22411_BGHEccosmart.png" alt="" width="250" height="43" /></p>
        <br>
        <p><strong>Estimado/a ADMINISTRADOR: El cliente %NOMBRE_SOLICITANTE% ha realizado una acción.</strong></p>
        <p>Confirmamos la realizaci&oacute;n de un nuevo pedido de Repuestos. El mismo se ha procesado correctamente y se encuentra <strong>%ESTADO_PEDIDO%</strong>.</p>
        <p>Se env&iacute;a a continuaci&oacute;n el detalle para su constancia.</p>


        <table class="customers">
            <tbody>
                <tr>
                    <td><strong>Orden de Pedido N&ordm;</strong></td>
                    <td>%ID_ORDEN_PEDIDO%</td>
                </tr>
                <tr>
                    <td>

                        <p><strong>Fecha</strong></p>
                    </td>
                    <td>

                        <p>%FECHA_PEDIDO%</p>
                    </td>
                </tr>
                <tr>
                    <td>

                        <p><strong>Monto</strong></p>
                    </td>
                    <td>

                        <p>%MONTO_ORDEN_PEDIDO%</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <p style="text-align: left;"><strong>Detalle de las piezas solicitadas</strong></p>
        <table class="customers">
            <tr>
                <th>Código</th>
                <th>Pieza</th>
                <th>Cant</th>
                <th>Precio Unit.</th>
                <th>Total</th>
                <th>Neto</th>
            </tr>


            %PIEZAS_ORDEN_PEDIDO%
        </table>
        <br>
        <br>
        <hr>

        <!-- contacto -->
        <hr />
        <p style="text-align: left;"><strong>Datos del Solicitante</strong></p>
        <table class="customers">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Domicilio</th>
                <th>user_id</th>                
            </tr>


            %DATOS_SOLICITANTE%
        </table>
        <br>
        <br>
        <hr>
        <!-- fin contacto -->

        
        
        <p class="centrado">
            <br>
            <a href="https://ecosmart.bgh.com.ar/">
                <img src="https://onsite.speedup.com.ar/imagenes/logo_local_BGH_22411_BGHEccosmart.png" width="125" height="21" />
            </a>
        </p>
        <div class="header_page">

            <p>Soluciones eficientes de climatización, iluminación y building management, para empresas, gobiernos y personas.</p>

        </div>
        <div class="pie_pagina">
            <br>
            <p><strong>© %ANIO_DERECHOS% BGH | Todos los derechos reservados.</strong></p>
            <br>
        </div>



    </div>


</body>

</html>