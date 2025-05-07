<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\TipoServicioOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TipoServicioOnsiteCollection extends ResourceCollection
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
          'data' => TipoServicioOnsiteResource::collection($this->collection),
          'links' => [
            'self' => route('api.v1.tipos_servicios_onsite.index')
          ]
        ];
    }
}
