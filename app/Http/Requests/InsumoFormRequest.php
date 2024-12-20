<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsumoFormRequest extends FormRequest
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
            'id_produto' => 'required',
            'valor_insumo_kg' => 'required',
            'unidade' => 'required',
            'quantidade_insumo' => 'required',
            'valor_total' => 'required',
            'valor_unitario' => 'required',
            'kg_insumo_total' => 'required'

        ];
    }

    public function messages()
    {
        return [
            //'name.required' => 'Mensagem exclusiva pro campo :attribute e o validador required!'
            'required' => 'Este campo é obrigatório!',
            'min' => 'Este campo deve ter pelo menos :min caracteres'
        ];
    }
}