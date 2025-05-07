<?php

namespace App\Http\Resources\Onsite;

use Illuminate\Http\Resources\Json\JsonResource;

class UnidadesExterioresOnsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      return [
        'type' => 'unidades_exteriores_onsite',
        'id' => (string) $this->getRouteKey(),
        'attributes' => [
          'company_id' => $this->company_id,
          'empresa_onsite_id' => $this->empresa_onsite_id,
          'sucursal_onsite_id' => $this->sucursal_onsite_id,
          'sistema_onsite_id' => $this->sistema_onsite_id,
          'clave' => $this->clave,
          'modelo' => $this->modelo,
          'serie' => $this->serie,
          'medida_figura_1_a' => $this->medida_figura_1_a,
          'medida_figura_1_b' => $this->medida_figura_1_b,
          'medida_figura_1_c' => $this->medida_figura_1_c,
          'medida_figura_1_d' => $this->medida_figura_1_d,
          'medida_figura_2_a' => $this->medida_figura_2_a,
          'medida_figura_2_b' => $this->medida_figura_2_b,
          'medida_figura_2_c' => $this->medida_figura_2_c,
          'anclaje_piso' => $this->anclaje_piso,
          'contra_sifon' => $this->contra_sifon,
          'mm_500_ultima_derivacion_curva' => $this->mm_500_ultima_derivacion_curva,
          'observaciones' => $this->observaciones,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ],
        'links' => [
          // 'self' => route('api.v1.sistemas_onsite.show', $this),
        ]
      ];
    }
}
