<?php

namespace App\Http\Requests\Fabrics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
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
            'model' => ['required',
                        'max:300',
                        Rule::unique('fabrics')
                        ->ignore($this->id)
                        ->where('color_id',$this->color_id)
                        ->where('material_id',$this->material_id)
            ],
            'quantity' => 'required|integer|min:0|max:100000',
            'weight' => 'required|integer|min:0|max:100000',
            'material_id' => [
                'sometimes',
                'integer',
                'exists:materials,id',
                Rule::unique('fabrics')
                ->ignore($this->id)
                ->where('model',$this->model)
                ->where('color_id',$this->color_id)

            ],
            'color_id' => [
                'sometimes',
                'integer',
                'exists:colors,id',
                Rule::unique('fabrics')
                ->ignore($this->id)
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
            'quantity.required' => 'Колличество единиц данной модели обязательно к заполнению',
            'quantity.integer' => 'Пожалуйста, введите число в качестве значения для поля "колличество"',
            'quantity.min' => 'Минимальное колличество не может быть меньше нуля',
            'quantity.max' => 'Сликшом большое колличество',
            'weight.required' => 'Вес в кг данной модели обязателен к заполнению',
            'weight.integer' => 'Пожалуйста, введите число в качестве значения для поля "вес"',
            'weight.min' => 'Минимальный вес не может быть меньше нуля',
            'weight.max' => 'Сликшом большой вес',
            'material_id.required' => 'Выберите материал',
            'material_id.integer' => 'Выберите корректный материал',
            'material_id.exists' => 'Выбранного вами материала не существует в базе данных',
            'color_id.required' => 'Выберите материал',
            'color_id.integer' => 'Выберите корректный цвет',
            'color_id.exists' => 'Выбранного вами цвета не существует в базе данных',
            'max' => 'Слишком длинное название'
        ];
    }
}
