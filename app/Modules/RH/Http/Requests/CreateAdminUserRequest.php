<?php

namespace App\Modules\RH\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'rib_number' => 'nullable|string|max:255',
            'rib' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ifu_number' => 'nullable|string|max:255',
            'ifu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'last_name.required' => 'Le nom est obligatoire.',
            'first_name.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email existe déjà.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'La photo doit être au format JPG, JPEG ou PNG.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
            'role_ids.*.exists' => 'Un ou plusieurs rôles n\'existent pas.',
        ];
    }
}
