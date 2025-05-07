<?php

namespace App\Exports;



use DB;

/* use Maatwebsite\Excel\Concerns\FromQuery; */

use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\FromQuery;

class ReparacionesOnsiteExport implements /* FromIterator */ FromQuery, WithHeadings /* WithCustomChunkSize */
{
	use Exportable;

	private $filters;
	private $userCompanyId;

	public function __construct(array $filters = [], $userCompanyId)
	{
		$this->filters = $filters;

		$this->userCompanyId = $userCompanyId;
	}





	/* 	public function iterator(): \Iterator
	{
		//$data = DB::table('view_reparaciones_onsite')->orderBy('id', 'desc');
		if (isset($this->filters['id_empresa']) && count($this->filters['id_empresa']) > 0) {
			//$data = $data->whereIn('id_empresa_onsite', $this->filters['id_empresa']);
			$data = DB::table('view_reparaciones_onsite')
				->whereIn('id_empresa_onsite', $this->filters['id_empresa'])
				->orderBy('id', 'desc');
		} else {
			//$data = $data->where('id_empresa_onsite', 1095);
			$data = DB::table('view_reparaciones_onsite')
				->where('id_empresa_onsite', 1095)
				->orderBy('id', 'desc');
		}

		// Filters
		if (isset($this->filters['estados_activo']) && $this->filters['estados_activo'] === 'on') {
			$data = $data->where('estado_activo', true);
		}


		if (!is_null($this->filters['fecha_creacion_desde']) && !is_null($this->filters['fecha_creacion_hasta'])) {
			$data = $data->whereBetween('created_at', [$this->filters['fecha_creacion_desde'], $this->filters['fecha_creacion_hasta']]);
		}

		if (!is_null($this->filters['fecha_ingreso_desde']) && !is_null($this->filters['fecha_ingreso_hasta'])) {
			$data = $data->whereBetween('fecha_ingreso', [$this->filters['fecha_ingreso_desde'], $this->filters['fecha_ingreso_hasta']]);
		}

		if (isset($this->filters['id_estado']) && count($this->filters['id_estado']) > 0) {
			$data = $data->whereIn('id_estado', $this->filters['id_estado']);
		}


		return $data->cursor()->getIterator();
	} */


	public function query()
	{
		$query = DB::table('view_reparaciones_onsite')
			->orderBy('id', 'desc');

		if (isset($this->filters['id_empresa']) && count($this->filters['id_empresa']) > 0) {
			$query = $query->whereIn('id_empresa_onsite', $this->filters['id_empresa']);
		} else {
			$query = $query->where('id_empresa_onsite', 1095);
		}

		if (isset($this->filters['estados_activo']) && $this->filters['estados_activo'] === 'on') {
			$query = $query->where('estado_activo', true);
		}

		if (!is_null($this->filters['fecha_creacion_desde']) && !is_null($this->filters['fecha_creacion_hasta'])) {
			$query = $query->whereBetween('created_at', [$this->filters['fecha_creacion_desde'], $this->filters['fecha_creacion_hasta']]);
		}

		if (!is_null($this->filters['fecha_ingreso_desde']) && !is_null($this->filters['fecha_ingreso_hasta'])) {
			$query = $query->whereBetween('fecha_ingreso', [$this->filters['fecha_ingreso_desde'], $this->filters['fecha_ingreso_hasta']]);
		}

		if (isset($this->filters['id_estado']) && count($this->filters['id_estado']) > 0) {
			$query = $query->whereIn('id_estado', $this->filters['id_estado']);
		}

		return $query;
	}



/* 
	public function chunkSize(): int
	{
		return 500;
	} */

	public function headings(): array
	{
		$data = [
			'ID',
			'company_id',
			'CLAVE',
			'ID_EMPRESA_ONSITE',
			'EMPRESA_ONSITE',
			'SUCURSAL_ONSITE_ID',
			'SUCURSAL_RAZON_SOCIAL',
			'SUCURSAL_DIRECCION',
			'SUCURSAL_TELEFONO',
			'ID_TERMINAL',
			'TERMINAL_MARCA',
			'TERMINAL_MODELO',
			'TERMINAL_SERIE',
			'TERMINAL_ROTULO',
			'ID_LOCALIDAD',
			'LOCALIDAD',
			'LOCALIDAD_ID_PROVINCIA',
			'LOCALIDAD_PROVINCIA',
			'LOCALIDAD_ESTANDARD',
			'LOCALIDAD_CODIGO_POSTAL',
			'LOCALIDAD_KM',
			'LOCALIDAD_ID_NIVEL',
			'LOCALIDAD_NIVEL',
			'LOCALIDAD_ATIENDE_DESDE',
			'LOCALIDAD_ID_TECNICO',
			'LOCALIDAD_TECNICO',
			'TAREA',
			'DETALLE_TAREA',
			'ID_TIPO_SERVICIO',
			'TIPO_SERVICIO',
			'ID_ESTADO',
			'ESTADO',
			'es_activo',
			'FECHA_INGRESO',
			'OBSERVACION_UBICACION',
			'ID_TECNICO_ASIGNADO',
			'TECNICO_ASIGNADO',
			'INFORME_TECNICO',
			'FECHA_COORDINADA',
			'VENTANA_HORARIA_COORDINADA',
			'FECHA_REGISTRACION_COORDINACION',
			'FECHA_NOTIFICADO',

			'FECHA_1_VENCIMIENTO',
			'FECHA_1_VISITA',

			'FECHA_VENCIMIENTO',
			'FECHA_CERRADO',
			'SLA_STATUS',
			'SLA_JUSTIFICADO',
			'MONTO',
			'MONTO_EXTRA',
			'LIQUIDADO_PROVEEDOR',
			'NRO_FACTURA_PROVEEDOR',

			'TIPO_CONEXION_LOCAL',
			'TIPO_CONEXION_PROVEEDOR',
			'CABLEADO',
			'CABLEADO_CANTIDAD_METROS',
			'CABLEADO_CANTIDAD_FICHAS',
			'INSTALACION_CARTEL',
			'INSTALACION_CARTEL_LUZ',
			'INSTALACION_BUZON',
			'CANTIDAD_HORAS_TRABAJO',
			'REQUIERE_NUEVA_VISITA',
			'CODIGO_ACTIVO_NUEVO1',
			'CODIGO_ACTIVO_RETIRADO1',
			'CODIGO_ACTIVO_DESCRIPCION1',
			'CODIGO_ACTIVO_NUEVO2',
			'CODIGO_ACTIVO_RETIRADO2',
			'CODIGO_ACTIVO_DESCRIPCION2',
			'CODIGO_ACTIVO_NUEVO3',
			'CODIGO_ACTIVO_RETIRADO3',
			'CODIGO_ACTIVO_DESCRIPCION3',
			'CODIGO_ACTIVO_NUEVO4',
			'CODIGO_ACTIVO_RETIRADO4',
			'CODIGO_ACTIVO_DESCRIPCION4',
			'CODIGO_ACTIVO_NUEVO5',
			'CODIGO_ACTIVO_RETIRADO5',
			'CODIGO_ACTIVO_DESCRIPCION5',
			'CODIGO_ACTIVO_NUEVO6',
			'CODIGO_ACTIVO_RETIRADO6',
			'CODIGO_ACTIVO_DESCRIPCION6',
			'CODIGO_ACTIVO_NUEVO7',
			'CODIGO_ACTIVO_RETIRADO7',
			'CODIGO_ACTIVO_DESCRIPCION7',
			'CODIGO_ACTIVO_NUEVO8',
			'CODIGO_ACTIVO_RETIRADO8',
			'CODIGO_ACTIVO_DESCRIPCION8',
			'CODIGO_ACTIVO_NUEVO9',
			'CODIGO_ACTIVO_RETIRADO9',
			'CODIGO_ACTIVO_DESCRIPCION9',
			'CODIGO_ACTIVO_NUEVO10',
			'CODIGO_ACTIVO_RETIRADO10',
			'CODIGO_ACTIVO_DESCRIPCION10',
			'MODEM_3G_4G_SIM_NUEVO',
			'MODEM_3G_4G_SIM_RETIRADO',
			'FIRMA_CLIENTE',
			'ACLARACION_CLIENTE',
			'FIRMA_TECNICO',
			'ACLARACION_TECNICO',
			'created_at'

		];
		return $data;
	}
}
