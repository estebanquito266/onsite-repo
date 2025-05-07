<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\ReparacionOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReparacionOnsiteCollection extends ResourceCollection
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
          'data' => ReparacionOnsiteResource::collection($this->collection),
          'links' => [
            'self' => route('api.v1.reparaciones_onsite.index')
          ]
        ];
    }
}
