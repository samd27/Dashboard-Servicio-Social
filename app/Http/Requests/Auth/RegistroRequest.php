<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegistroRequest extends FormRequest
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nombre_cuenta' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'min:12', 'max:13', 'unique:cuentas'],
            'telefono' => ['required', 'string', 'size:10'],
            
            'email_2_opcional' => [
                'nullable', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'different:email'
            ],
        ];
    }
}