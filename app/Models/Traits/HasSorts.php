<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait HasSorts
{
  public function scopeApplySorts(Builder $query, $sort)
  {
    //Si no pasaron el parametro con los campos permitidos para ordenar devuelve un error
    if (!property_exists($this, 'campos_permitidos_para_ordenar')) {
      abort(500, 'Falta la propiedad public $campos_permitidos_para_ordenar en la clase' . get_class($this));
    }
    //Si no hay campos por que ordenar no hace nada
    if (is_null($sort)) {
      return;
    }

    $campos_a_ordenar = Str::of($sort)->explode(',');

    foreach ($campos_a_ordenar as $campo_a_ordenar) {
     
      $orden = 'asc';
      
      if (Str::of($campo_a_ordenar)->startsWith('-')) {
        $orden = 'desc';
        // Quita el primer caracter (-) del nombre del parametro que tiene el nombre del campo
        $campo_a_ordenar = Str::of($campo_a_ordenar)->substr(1);
      }

      // Si recibe un campo no permitido para ordenar devuelve un 400 (metodo not allowed)
      if (!collect($this->campos_permitidos_para_ordenar)->contains($campo_a_ordenar)) {
        abort(400, "No se puede ordenar por el campo: {$campo_a_ordenar}");
      }

      $reparaciones_onsite = $query->orderBy($campo_a_ordenar, $orden);
    }
  }
}