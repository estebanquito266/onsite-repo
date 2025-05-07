<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\EstadoOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EstadoOnsiteCollection extends ResourceCollection
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
          'data' => EstadoOnsiteResource::collection($this->collection),
          'links' => [
            'self' => route('api.v1.estados_onsite.activos')
          ]
        ];
    }
}
