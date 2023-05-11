<?php

namespace App\Http\Requests\Fabrics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

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
            'model' => 'required|max:300|unique:fabrics,model,'.$this->id,
            'quantity' => 'required|integer|min:0',
            'materials' => 'sometimes|string|max:1500|nullable',
            'colors' => 'sometimes|array|exists:colors,id'
        ];
    }

    public function messages()
    {
        return [
            'model.required' => 'Введите название/номер модели',
            'model.max' => 'Слишком длинное название',
            'model.unique' => 'Такая модель уже существует на складе',
            'quantity.required' => 'Колличество единиц данной модели обязательно к заполнению',
            'quantity.integer' => 'Пожалуйста, введите число в качестве значения для поля "колличество"',
            'materials.string' => 'Ввидите значения в поле "материалы"',
            'colors.array' => 'Поле цвета должно быть массивом',
            'colors.exists' => 'Одного из выбранных вами цветов не существует в базе данных',
            'max' => 'Слишком длинное название'
        ];
    }
}
