<?php

namespace App\Modules\Inscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePendingStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:pending_students,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'entry_level_id' => 'required|exists:entry_levels,id',
            'entry_diploma_id' => 'required|exists:entry_diplomas,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'first_name.required' => 'Le prénom est requis.',
            'last_name.required' => 'Le nom est requis.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'entry_level_id.required' => 'Le niveau d\'entrée est requis.',
            'entry_level_id.exists' => 'Le niveau d\'entrée sélectionné n\'existe pas.',
            'entry_diploma_id.required' => 'Le diplôme d\'entrée est requis.',
            'entry_diploma_id.exists' => 'Le diplôme d\'entrée sélectionné n\'existe pas.',
        ];
    }
}
