<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\throwException;

class StoreIngresos extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        return match ($this->method()) {
            'POST' => [
                'nombre' => 'required|string|max:30',
                'procedencia' => 'required|string',
                'asunto' => 'required|string|max:30',
                'contacto' => 'required|string|max:50',
                'fecha' => 'required',
                'hora_agendada' => 'required',
                'personal_id' => 'required',
            ]
        };
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El campo nombre es requerido.',
            'procedencia.required' => 'El campo procedencia es requerido.',
            'asunto.required' => 'El campo asunto es requerido.',
            'contacto.required' => 'El campo contacto es requerido.',
            'fecha.required' => 'El campo fecha es requerido.',
            'hora_agendada' => 'El campo hora_agendada es requerido.',
            'personal_id' => 'El campo personal_id es requerido.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json(
                [
                    'status' => 'fail',
                    'data' => $errors
                ],
                422
            )
        );
    }
}
