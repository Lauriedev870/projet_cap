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
        // Extraire le phone depuis le JSON contacts si disponible
        $phone = null;
        if ($this->personalInformation && $this->personalInformation->contacts) {
            $contacts = is_array($this->personalInformation->contacts) 
                ? $this->personalInformation->contacts 
                : json_decode($this->personalInformation->contacts, true);
            $phone = $contacts['phone'] ?? null;
        }

        return [
            'id' => $this->id,
            'email' => $this->personalInformation?->email,
            'first_name' => $this->personalInformation?->first_names,
            'last_name' => $this->personalInformation?->last_name,
            'phone' => $phone,
            'gender' => $this->personalInformation?->gender,
            'status' => $this->status,
            'submitted_at' => $this->created_at?->toISOString(),
            'department' => $this->department?->name,
            'entry_diploma' => $this->whenLoaded('entryDiploma', function () {
                return [
                    'id' => $this->entryDiploma->id,
                    'name' => $this->entryDiploma->name,
                    'abbreviation' => $this->entryDiploma->abbreviation,
                    'entry_level' => $this->entryDiploma->entry_level,
                ];
            }),
            'student_pending_students' => $this->whenLoaded('studentPendingStudents', function () {
                return $this->studentPendingStudents->map(function ($sps) {
                    return [
                        'id' => $sps->id,
                        'student' => $sps->whenLoaded('student', function () {
                            return [
                                'id' => $sps->student->id,
                                'student_id_number' => $sps->student->student_id_number,
                            ];
                        }),
                    ];
                });
            }),
            'files' => $this->whenLoaded('files', function () {
                return $this->files->map(function ($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->name,
                        'path' => $file->path,
                        'mime_type' => $file->mime_type,
                        'size' => $file->size,
                        // Ajoutez d'autres champs si nécessaire
                    ];
                });
            }),
        ];
    }
}
