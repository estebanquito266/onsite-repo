<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Parametro extends Model
{
	protected $table = "parametros";

	protected $fillable = [
		'nombre', 'descripcion', 'id_tipo_parametro', 'id_plantilla_mail_base', 'tipo_valor',
		'valor_numerico', 'valor_cadena', 'valor_texto', 'valor_fecha', 'valor_boolean', 'valor_decimal',
		'company_id',
	];

	public static function buscarParametro($parametro)
	{
		return Parametro::where("nombre", $parametro)
			->first();
	}
}
