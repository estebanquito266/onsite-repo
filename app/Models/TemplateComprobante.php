<?php

namespace App\Models;

use App\Models\Admin\Company;
use App\Models\Onsite\CompradorOnsite;
use App\Models\EmpresaInstaladora\EmpresaInstaladoraOnsite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateComprobante extends Model
{
	const DISCLAIMER_REPUESTOS = 7;
	const DISCLAIMER_SOLICITUDES = 6;
	const COMPROBANTE_VISITA = 8;

	protected $table = "templates_comprobantes";

	protected $fillable = [
		'nombre', 'cuerpo',  'tipo_comprobante',
		'company_id',
	];

}
