<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'image'=>$this->plant_img,
            'plant name'=>$this->plant_name,
            'conditon'=>$this->condition,
            'disease'=>$this->disease
        ];
    }
}
