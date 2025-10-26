<?php

namespace App\Modules\RH\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
            'rib_number' => $this->rib_number,
            'rib' => $this->rib,
            'ifu_number' => $this->ifu_number,
            'ifu' => $this->ifu,
            'bank' => $this->bank,
            'status' => $this->status,
            'grade_id' => $this->grade_id,
            'grade' => $this->whenLoaded('grade', function () {
                return [
                    'id' => $this->grade->id,
                    'name' => $this->grade->name,
                    'abbreviation' => $this->grade->abbreviation,
                ];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
