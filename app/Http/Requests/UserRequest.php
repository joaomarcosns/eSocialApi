<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome de usuário é obrigatório',
            'name.max' => 'O Nome de usuário apenas pode ter max characters',
            'name.string' => 'Não pode conter números nesse campo',

        ];
    }
}
