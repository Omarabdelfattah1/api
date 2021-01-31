<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankingsResource extends JsonResource
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
            "position"=>$this->position,
            "team_name"=>$this->name,
            "team_id"=>$this->id,
            "Played"=>$this->played,
            "won"=>$this->won,
            "drawn"=>$this->drawn,
            "Lost"=>$this->lost,
            "points"=>$this->points,
        ];
    }
}
