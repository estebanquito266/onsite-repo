<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\SucursalOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SucursalOnsiteCollection extends ResourceCollection
{
  /**
   * Transform the resource collection into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      'data' => SucursalOnsiteResource::collection($this->collection),
      'links' => [
        'self' => route('api.v1.sucursales_onsite.index')
      ]
    ];
  }
}
