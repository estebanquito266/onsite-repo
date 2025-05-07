<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\EmpresaOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmpresaOnsiteCollection extends ResourceCollection
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
          'data' => EmpresaOnsiteResource::collection($this->collection),
          'links' => [
            'self' => route('api.v1.empresas_onsite.index')
          ]
        ];
    }
}
