<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlantillaMail extends Model
{
	protected $table = "plantillas_mails";

	protected $fillable = [
		'id', 'referencia', 'from', 'from_nombre', 'subject', 'body', 'cc', 'cc_nombre', 'plantilla_mail_base',
		'body_txt',
		'company_id',
	];
}
