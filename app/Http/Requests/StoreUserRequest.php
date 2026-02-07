<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dui' => ['required', 'string', 'unique:users,dui', 'regex:/^\d{8}-\d$/'],
            'hiring_date' => ['nullable', 'date'], // Lo dejamos nullable o sometimes porque puede no venir
            'phone_number' => ['nullable', 'string', 'max:15'], 
            'birth_date' => ['required', 'date', 'before:today'], // Validacion "anterior a hoy"

            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
        ];
    }
}
