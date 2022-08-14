<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DomainsStoreRequest extends FormRequest
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
            'name' => ['required', 'max:255'],
            'tld' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do domínio é obrigatório',
            'name.max' => 'O tamanho máximo do nome do é de :max',
            'tld.required' => 'O tld do domínio é obrigatório',
        ];
    }
}
