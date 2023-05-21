<?php

namespace App\Http\Requests\Fabrics;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
            'model' => 'sometimes|string|max:500',
            'quantity' => 'sometimes|integer|min:0',
            'weight' => 'sometimes|integer|min:0',
            'material_id' => 'sometimes|integer|exists:materials,id',
            'color_id' => 'sometimes|integer|exists:colors,id',
            'quantity_from' => 'sometimes|integer|min:0|max:100000',
            'quantity_to' => 'sometimes|integer|min:1|max:100000',
            'weight_from' => 'sometimes|integer|min:0|max:100000',
            'weight_to' => 'sometimes|integer|min:1|max:100000',
        ];
    }

    public function messages()
    {
        return [
            'model.string' => 'Название/Номер модели должно быть строкой',
            'model.max' => 'Слишком длинное название',
            'quantity.integer' => 'Поле колличество должно быть числом !',
            'quantity.min' => 'Минимальное колличество должно быть не меньше нуля',
            'weight.integer' => 'Поле вес должно быть числом !',
            'weight.min' => 'Минимальный вес должен быть не меньше нуля',
            'quantity_from.min' => 'Минимальное колличество должно быть не меньше нуля',
            'quantity_from.max' => 'Слишком большое колличество',
            'quantity_to.min' => 'Минимальное колличество должно быть не меньше 1',
            'quantity_to.max' => 'Слишком большое колличество',
            'weight_from.min' => 'Минимальный вес должен быть не меньше нуля',
            'weight_from.max' => 'Слишком большой вес',
            'weight_to.min' => 'Минимальный вес должен быть не меньше 1',
            'weight_to.max' => 'Слишком большой вес',
            'material_id.integer' => 'Выберите материал из списка',
            'material_id.exists' => 'Такого материала не существует в базе',
            'color_id.integer' => 'Выберите цвет из списка',
            'color_id.exists' => 'Такого материала не существует в базе',
        ];
    }
}
