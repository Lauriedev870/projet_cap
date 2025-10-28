<?php

namespace App\Modules\Inscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'year_start' => ['required', 'date'],
            'year_end' => ['required', 'date', 'after:year_start'],
            'submission_start' => ['required', 'date', 'after_or_equal:year_start'],
            'submission_end' => ['required', 'date', 'after:submission_start', 'before:year_end'],
            'departments' => ['sometimes', 'array', 'min:1'],
            'departments.*' => ['integer', 'exists:departments,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'year_start.required' => 'La date de début d\'année est requise',
            'year_end.required' => 'La date de fin d\'année est requise',
            'year_end.after' => 'La date de fin doit être après la date de début',
            'submission_start.required' => 'La date de début de soumission est requise',
            'submission_start.after_or_equal' => 'La date de début de soumission doit être après ou égale à la date de début d\'année',
            'submission_end.required' => 'La date de fin de soumission est requise',
            'submission_end.after' => 'La date de fin de soumission doit être après la date de début de soumission',
            'submission_end.before' => 'La date de fin de soumission doit être avant la date de fin d\'année',
            'departments.array' => 'Les départements doivent être un tableau',
            'departments.min' => 'Au moins un département est requis',
            'departments.*.integer' => 'Chaque département doit être un ID valide',
            'departments.*.exists' => 'Le département spécifié n\'existe pas',
        ];
    }
}
