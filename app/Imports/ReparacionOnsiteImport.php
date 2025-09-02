<?php

namespace App\Imports;


use App\Services\Onsite\Reparacion\ImportacionService;
use App\Services\Onsite\ReparacionOnsiteService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class   ReparacionOnsiteImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnError, SkipsOnFailure, WithEvents, SkipsEmptyRows
{
    use RemembersChunkOffset;
    use RemembersRowNumber;
    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;
    private $rows = 0;

    protected $dataReparacion;
    protected $lineaClaseLogs;
    protected $reparacionesOnsiteService;
    protected $importacionService;
    protected $companyId;
    protected $user_id;
    
    function __construct(ReparacionOnsiteService $ReparacionOnsiteService,ImportacionService $ImportacionService) {

        /* defino variables generales en el construct para no definir cada vez que se lee una fila del archivo de excel */
        $this->companyId = Session::get('userCompanyIdDefault');
        $this->user_id = Auth::user()->id;
        $this->reparacionesOnsiteService = $ReparacionOnsiteService;
        $this->importacionService = $ImportacionService;
        $this->lineaClaseLogs = ' - ' . get_class($this) . ' - LINE: ';
    }


    public function withValidator($validator)
    {
        /* 
            Esta function se ejecuta automática por la clase cuando se envía el request del archivo de 
            excel desde el controller. La usamos también para validar las variables necesarias (company, etc)
            Se declaran todas las variables y se analiza validez. 
            En caso de error se agrega un $erros a la validación de Laravel/Excel 
            Mediante $validator->getData() se obtiene la misma info Row $row

            https://docs.laravel-excel.com/3.1/imports/validation.html#configuring-the-validator
         */

         $this->importacionService->setUserId($this->user_id);
    }

    public function prepareForValidation($data, $index)
    {

        //Log::info($data);

        $this->dataReparacion = $data;
        return $data;
    }

 


    public static function afterImport(AfterImport $event)
    {
        //actions after import
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function onRow(Row $row)
    {
        
        ++$this->rows;
        try {

            

            foreach ($this->dataReparacion as $key => $value) {
                if (str_starts_with($key, 'fecha') && $value) {
                    $this->dataReparacion[$key] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                        ->format('Y-m-d');
                }
            }


            //Log::info($this->dataReparacion);

            $clave = (isset($this->dataReparacion['clave'])) ? $this->dataReparacion['clave'] : null;
            $reparacionOnsiteId = (isset($this->dataReparacion['id_reparacion'])) ? $this->dataReparacion['id_reparacion'] : null;
            $nroTerminal = (isset($this->dataReparacion['nro_terminal'])) ? $this->dataReparacion['nro_terminal'] : null;
            $tipoServicioId = (isset($this->dataReparacion['id_tipo_servicio'])) ? $this->dataReparacion['id_tipo_servicio'] : null;
            $empresaId = (isset($this->dataReparacion['id_empresa_onsite'])) ? $this->dataReparacion['id_empresa_onsite'] : null;
            $codigoSucursal = (isset($this->dataReparacion['codigo_sucursal'])) ? $this->dataReparacion['codigo_sucursal'] : null;
            $sucursalId = (isset($this->dataReparacion['id_sucursal'])) ? $this->dataReparacion['id_sucursal'] : null;
            $estadoId = (isset($this->dataReparacion['id_estado_onsite'])) ? $this->dataReparacion['id_estado_onsite'] : null;
            $sucursalRazonSocial = (isset($this->dataReparacion['sucursal_razon_social'])) ? $this->dataReparacion['sucursal_razon_social'] : null;
            $sucursalDireccion = (isset($this->dataReparacion['sucursal_direccion'])) ? $this->dataReparacion['sucursal_direccion'] : null;
            $sucursalTelefono = (isset($this->dataReparacion['sucursal_telefono'])) ? $this->dataReparacion['sucursal_telefono'] : null;
            $sucursalContacto = (isset($this->dataReparacion['sucursal_contacto'])) ? $this->dataReparacion['sucursal_contacto'] : '--';
            $localidadCodigoPostal = (isset($this->dataReparacion['localidad_codigo_postal'])) ? $this->dataReparacion['localidad_codigo_postal'] : null;
            $localidadId = (isset($this->dataReparacion['localidad_onsite_id'])) ? $this->dataReparacion['localidad_onsite_id'] : null;


            $reparacionOnsite = $this->importacionService->getReparacionOnsite($reparacionOnsiteId, $clave);

            if (!$reparacionOnsite && !$clave) {
                $requestClave = new Request([
                    'id_empresa_onsite' => $empresaId
                ]);
                $clave = $this->reparacionesOnsiteService->getClaveReparacionOnsite($requestClave);
            }

            $estadoReparacionOnsiteId = $estadoId;

            $tipoServicioOnsite = $this->importacionService->getTipoServicioOnsite($tipoServicioId);

            $empresaOnsite = $this->importacionService->getEmpresaOnsite($empresaId);

            $sucursalOnsite = null;
            if ($empresaOnsite) {
                $paramSucursal = [
                    'empresaId' => $empresaId,
                    'codigoSucursal' => $codigoSucursal,
                    'sucursalId' => $sucursalId,
                    'sucursalRazonSocial' => $sucursalRazonSocial,
                    'sucursalDireccion' => $sucursalDireccion,
                    'sucursalTelefono' => $sucursalTelefono,
                    'sucursalContacto' => $sucursalContacto,
                    'localidadId' => $localidadId,
                    'localidadCodigoPostal' => $localidadCodigoPostal,
                    'companyId' => $this->companyId,
                ];
                $sucursalOnsite = $this->importacionService->getSucursalOnsite($paramSucursal);
            }


            if (!$reparacionOnsite) {


                $estadoReparacionOnsiteId = $this->importacionService->getEstadoReparacionOnsiteId($estadoId);

                $terminal = $this->importacionService->getTerminalOnsite($nroTerminal, $sucursalOnsite, $empresaOnsite, $this->rows, $this->dataReparacion);

                $reparacionOnsite = $this->importacionService->insertReparacionOnsite($clave, $empresaOnsite, $sucursalOnsite, $terminal, $tipoServicioOnsite, $estadoReparacionOnsiteId, $this->dataReparacion, $this->companyId);

                if ($reparacionOnsite != null) {

                    ++$this->rows;
                    $this->reparacionesOnsiteService->actualizarImagenes($reparacionOnsite['reparacionOnsite'], $this->dataReparacion);
                } else {
                    //$this->salteadosError++;
                    //$this->filasError = $this->filasError . ' ' . (intval($fila) + 2) . ',';
                }
            } else {

                //busca estado
                $idEstadoOld = null;
                $idEstadoNew = null;

                if ($estadoReparacionOnsiteId) {
                    $idEstadoOld = $reparacionOnsite->id_estado;
                    $idEstadoNew = $estadoReparacionOnsiteId;
                }

                $reparacionOnsite = $this->importacionService->updateReparacionOnsite($reparacionOnsite, $estadoReparacionOnsiteId, $tipoServicioOnsite, $this->dataReparacion, $this->companyId, $this->user_id);

                if ($reparacionOnsite != null) {
                    //$this->reparacionesActualizadas++;
                    //$this->filasActualizadas = $this->filasActualizadas . ' ' . ($fila + 2) . ',';
                } else {
                    //$this->salteadosExistentes++;
                    //$this->filasExistentes = $this->filasExistentes . ' ' . ($fila + 2) . ',';
                }
            }

            $succes = true;
        } catch (\Throwable $th) {
            Log::alert('error creando la reparacion ' . get_class($this) . '-' . __LINE__);
            Log::alert($th);
        }

        
        
    }


    public function rules(): array
    {
        return [
            //'marca' => [new RepuestoMirgorRule(['marca', 'modelos', 'codigo_sap', 'codigo_oem'], $this->dataAssurantDeducibles)],
        ];
    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.        

        return $e;
    }


    public function getRowCount()
    {
        return $this->rows;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
