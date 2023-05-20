<?php

namespace App\Http\Requests\Materials;

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
            'name' => 'required|max:300|unique:materials,name,'.$this->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Введите название материала',
            'name.max' => 'Слишком длинное название',
            'name.unique' => 'Такой материал уже добавлен',
        ];
    }
}
