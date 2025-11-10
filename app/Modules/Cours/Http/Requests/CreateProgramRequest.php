<?php

namespace App\Modules\Cours\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_group_id' => 'required|exists:class_groups,id',
            'course_element_professor_id' => [
                'required',
                'exists:course_element_professor,id',
                // Vérifier que cette combinaison n'existe pas déjà
                function ($attribute, $value, $fail) {
                    $exists = \App\Modules\Cours\Models\Program::where('class_group_id', $this->class_group_id)
                        ->where('course_element_professor_id', $value)
                        ->exists();
                    
                    if ($exists) {
                        $fail('Ce cours est déjà assigné à ce groupe de classe.');
                    }
                },
            ],
            'weighting' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        return;
                    }
                    
                    // Vérifier que toutes les valeurs sont numériques
                    foreach ($value as $key => $weight) {
                        if (!is_numeric($weight)) {
                            $fail("La pondération '{$key}' doit être un nombre.");
                            return;
                        }
                    }
                    
                    // Vérifier que la somme fait 100
                    $sum = array_sum($value);
                    if ($sum != 100) {
                        $fail("La somme des pondérations doit être égale à 100 (actuellement: {$sum}).");
                    }
                },
            ],
            'weighting.*' => 'numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'class_group_id.required' => 'Le groupe de classe est obligatoire.',
            'class_group_id.exists' => 'Le groupe de classe sélectionné n\'existe pas.',
            'course_element_professor_id.required' => 'L\'assignation cours-professeur est obligatoire.',
            'course_element_professor_id.exists' => 'L\'assignation cours-professeur sélectionnée n\'existe pas.',
            'weighting.array' => 'La pondération doit être un tableau.',
            'weighting.*.numeric' => 'Chaque pondération doit être un nombre.',
            'weighting.*.min' => 'Chaque pondération doit être supérieure ou égale à 0.',
            'weighting.*.max' => 'Chaque pondération doit être inférieure ou égale à 100.',
        ];
    }

    /**
     * Exemple de structure pour la pondération:
     * {
     *     "CC": 30,
     *     "TP": 20,
     *     "EXAMEN": 50
     * }
     * Total doit faire 100
     */
    public function attributes(): array
    {
        return [
            'class_group_id' => 'groupe de classe',
            'course_element_professor_id' => 'assignation cours-professeur',
            'weighting' => 'pondération',
        ];
    }
}
