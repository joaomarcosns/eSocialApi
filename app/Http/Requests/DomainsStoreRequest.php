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
            'name' => ['required', 'max:255', 'unique:domains'],
            'tld' => ['required', 'max:15'],
            'register' => ['required', 'max:255'],
            'created_at' => ['required'],
            'updated_at' => ['required'],
            'nameserver_1' => ['required'],
            'nameserver_2' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome do domínio é obrigatório',
            'name.max' => 'O tamanho máximo do nome do é de :max',
            'name.unique' => 'Nome do domínio já existe',
            'tld.required' => 'O tld do domínio é obrigatório',
            'tld.max' => 'O tamanho máximo do tld do é de :max',
            'register.required' => 'O nome do registrador é obrigatório',
            'register.max' => 'O tamanho máximo do nome do registrador é :max',
            'created_at.required' => 'O campo é obrigatório',
            'updated_at.required' => 'O campo é obrigatório',
            'nameserver_1.required' =>'O campo é obrigatório',
            'nameserver_2.required' =>'O campo é obrigatório',
        ];
    }
}
