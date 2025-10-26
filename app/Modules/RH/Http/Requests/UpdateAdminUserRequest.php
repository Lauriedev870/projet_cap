<?php

namespace App\Modules\RH\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('admin_user');
        
        return [
            'last_name' => 'sometimes|required|string|max:255',
            'first_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
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
            'email.unique' => 'Cet email existe déjà.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'La photo doit être au format JPG, JPEG ou PNG.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ];
    }
}
