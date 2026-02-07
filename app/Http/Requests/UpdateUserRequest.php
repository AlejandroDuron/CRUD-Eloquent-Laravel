<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        // Si es PATCH, usamos la regla 'sometimes' (solo valida si el campo está presente).
        // Si es PUT, usamos 'required' (el campo DEBE estar presente).

        $requirement = $this->isMethod('patch') ? 'sometimes' : 'required';

        return [
            'name' => [$requirement, 'string', 'max:255'],
            'lastname' => [$requirement, 'string', 'max:255'],
            'username' => [$requirement, 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user)],
            'email' => [$requirement, 'email', Rule::unique('users', 'email')->ignore($this->user)],
            
            'dui' => [$requirement, 'string', 'regex:/^\d{8}-\d$/', Rule::unique('users', 'dui')->ignore($this->user)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'birth_date' => [$requirement, 'date', 'before:today'],
            'hiring_date' => ['nullable', 'date'],
        ];
    }
}
