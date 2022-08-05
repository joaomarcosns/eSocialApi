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
            'name' => ['required', 'max:255', 'min:3', 'string'],
            'email' => ['required', 'email', 'unique:users', 'confirmed'],
            'password' => ['required', 'min:3','confirmed']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O Nome de usuário é obrigatório',
            'name.max' => 'O Nome de usuário apenas pode ter :max characters',
            'name.string' => 'Não pode conter números nesse campo',
            'name.min' => 'O Nome de usuário tem ser maior que :min characters',
            'email.required' => 'O campo de e-mail é obrigatório',
            'email.email' => 'O campo de e-mail deve ser do tipo e-mail',
            'email.unique' => 'Essa conta já existe',
            'email.confirmed' => 'A confirmação do e-mail não corresponde.',
            'password.required' => 'O campo senha é obrigatório',
            'password.min' => 'A senha deve ser no mínimo :min caracteres',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }
}
