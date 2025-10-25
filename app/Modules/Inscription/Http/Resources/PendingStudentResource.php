<?php

namespace App\Modules\Inscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingStudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'status' => $this->status,
            'submitted_at' => $this->submitted_at?->toISOString(),
            'entry_level' => $this->whenLoaded('entryLevel', function () {
                return [
                    'id' => $this->entryLevel->id,
                    'name' => $this->entryLevel->name,
                    'description' => $this->entryLevel->description,
                ];
            }),
            'entry_diploma' => $this->whenLoaded('entryDiploma', function () {
                return [
                    'id' => $this->entryDiploma->id,
                    'name' => $this->entryDiploma->name,
                ];
            }),
            'student_pending_students' => $this->whenLoaded('studentPendingStudents', function () {
                return $this->studentPendingStudents->map(function ($sps) {
                    return [
                        'id' => $sps->id,
                        'student' => $sps->whenLoaded('student', function () {
                            return [
                                'id' => $sps->student->id,
                                'name' => $sps->student->name,
                                'email' => $sps->student->email,
                            ];
                        }),
                        'status' => $sps->status,
                        'notes' => $sps->notes,
                    ];
                });
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
