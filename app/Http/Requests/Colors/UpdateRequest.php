<?php

namespace App\Http\Requests\Colors;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'required|max:300|unique:colors,name,'.$this->id,
            'hex_code' => 'max:300'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите название цвета',
            'name.max' => 'Слишком длинное название',
            'name.unique' => 'Такой цвет уже существует',
            'max' => 'Слишком длинное '
        ];
    }
}
