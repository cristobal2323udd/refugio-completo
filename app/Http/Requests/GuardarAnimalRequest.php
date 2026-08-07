<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GuardarAnimalRequest extends FormRequest
{
    /**
     * Ojo: el archivo se genera con false. Así, cada envío responde 403.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre'  => 'required|string|max:60',
            'edad'    => 'required|integer|min:0|max:30',
            'especie' => 'required|in:perro,gato,pajaro,conejo',
        ];
    }

    /**
     * Los mensajes en español. Sin esto salen en inglés.
     */
    public function messages(): array
    {
        return [
            'nombre.required'  => 'El nombre es obligatorio.',
            'edad.required'    => 'La edad es obligatoria.',
            'edad.integer'     => 'La edad tiene que ser un número entero.',
            'especie.required' => 'Elige una especie.',
        ];
    }
}
