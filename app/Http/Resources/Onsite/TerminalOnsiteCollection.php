<?php

namespace App\Http\Resources\Onsite;

use App\Http\Resources\Onsite\TerminalOnsiteResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TerminalOnsiteCollection extends ResourceCollection
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
      'data' => TerminalOnsiteResource::collection($this->collection),
      'links' => [
        'self' => route('api.v1.terminales_onsite.index')
      ]
    ];
  }
}
