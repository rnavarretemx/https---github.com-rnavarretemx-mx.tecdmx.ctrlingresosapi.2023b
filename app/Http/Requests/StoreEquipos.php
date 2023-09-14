<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class StoreEquipos extends FormRequest
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
                'marca' => 'required|string|max:30',
                'modelo' => 'required|string|max:30',
                'no_serie' => 'required|string',
                'descripcion' => 'string',
                'cod_ingreso' => 'required',
            ]
        };
    }

    public function messages()
    {
        return [
            'marca.required' => 'El campo marca es requerido.',
            'modelo.required' => 'El campo modelo es requerido.',
            'no_serie.required' => 'El campo no_serie es requerido.',
            'cod_ingreso.required' => 'El campo cod_ingreso es requerido.'
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
