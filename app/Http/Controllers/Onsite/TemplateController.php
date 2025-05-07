<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Models\Onsite\SistemaOnsite;
use App\Services\Onsite\CompradoresOnsiteService;
use App\Services\Onsite\SistemaOnsiteService;
use App\Services\Onsite\TemplatesService;
use DateTime;
use Illuminate\Http\Request;
use Log;
use Session;

class TemplateController extends Controller
{
	
protected $templateService;

	public function __construct(TemplatesService $templateService)
	{				
	$this->templateService = $templateService;

	}

	public function getTemplate($idTemplate)
	{
		$template = $this->templateService->getTemplate($idTemplate);

		

		return response()->json($template);
	}


	public function getTemplateSolicitud($idTemplate)
	{
		$template = $this->templateService->getTemplate($idTemplate);

		
		//setlocale(LC_TIME, 'es_ES','es_ES.UTF-8');
		setlocale(LC_ALL,'es_ES');
		date_default_timezone_set ('America/Argentina/Buenos_Aires');

		$fecha_str = date('d/m/Y');
        $date = DateTime::createFromFormat("d/m/Y", $fecha_str);
		//$fecha_actual =  strftime("%A, %d de %B de %Y", $date->getTimestamp());
		/* $fecha_actual = strftime("%A %d de %B del %Y"); */
		$fecha_actual = date('d/m/Y');

		$template_solicitud = str_replace("%FECHA_ACTUAL%", $fecha_actual, $template->cuerpo);

		return response()->json($template_solicitud);
	}

	


	
	


	

	
}
