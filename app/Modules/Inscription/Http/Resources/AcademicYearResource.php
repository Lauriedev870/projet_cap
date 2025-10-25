<?php

namespace App\Modules\Inscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicYearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'academic_year' => $this->academic_year,
            'year_start' => $this->year_start,
            'year_end' => $this->year_end,
            'submission_start' => $this->submission_start?->toISOString(),
            'submission_end' => $this->submission_end?->toISOString(),
            'reclamation_start' => $this->reclamation_start?->toISOString(),
            'reclamation_end' => $this->reclamation_end?->toISOString(),
            'submission_periods' => $this->whenLoaded('submissionPeriods', function () {
                return SubmissionPeriodResource::collection($this->submissionPeriods);
            }),
            'reclamation_periods' => $this->whenLoaded('reclamationPeriods', function () {
                return ReclamationPeriodResource::collection($this->reclamationPeriods);
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
