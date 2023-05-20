<?php

namespace App\Http\Requests\Colors;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        return [
            'name' => 'required|min:4|max:300|unique:colors',
            'hex_code' => 'max:300'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите название цвета',
            'name.max' => 'Слишком длинное название',
            'name.min' => 'Введите минимум 4 символа в названии',
            'name.unique' => 'Такой цвет уже существует',
            'max' => 'Слишком длинное '
        ];
    }
}
