<?php

namespace App\Http\Requests\Fabrics;

use Illuminate\Validation\Rule;
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
            'model' => ['required',
                        'max:300',
                        Rule::unique('fabrics')
                        ->where('color_id',$this->color_id)
                        ->where('material_id',$this->material_id)
            ],
            'quantity' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'material_id' => [
                            'sometimes',
                            'integer',
                            'exists:materials,id',
                            Rule::unique('fabrics')
                            ->where('model',$this->model)
                            ->where('color_id',$this->color_id)

            ],
            'color_id' => [
                'sometimes',
                'integer',
                'exists:colors,id',
                Rule::unique('fabrics')
                ->where('model',$this->model)
                ->where('material_id',$this->material_id)
            ],
        ];
    }
    public function messages()
    {
        return [
            'model.required' => 'Введите название/номер модели',
            'model.max' => 'Слишком длинное название',
            'model.unique' => 'Такая модель с таким же материалом и цветом существует на складе',
            'weight.required' => 'Поле вес обязательно к заполнению',
            'weight.integer' => 'Пожалуйста, введите число в качестве значения для поля "вес"',
            'weight.min' => 'Вес не может быть меньше нуля',
            'quantity.required' => 'Колличество единиц данной модели обязательно к заполнению',
            'quantity.integer' => 'Пожалуйста, введите число в качестве значения для поля "колличество"',
            'quantity.min' => 'Колличество не может быть меньше нуля',
            'material_id.unique' => 'Такая модель с таким же материалом и цветом существует на складе',
            'material_id.integer' => 'Выберите корректный материал',
            'material_id.exists' => 'Выбранного вами материала не существует в базе данных',
            'color_id.unique' => 'Такая модель с таким же материалом и цветом существует на складе',
            'color_id.integer' => 'Выберите корректный цвет',
            'color_id.exists' => 'Выбранного вами цвета не существует в базе данных',
            'max' => 'Слишком длинное название'
        ];
    }
}
