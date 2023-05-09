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
            'materials' => 'sometimes|string|max:1000',
            'colors' => 'sometimes|string|max:1500',
            'quantity_from' => 'sometimes|integer|min:0|max:100000',
            'quantity_to' => 'sometimes|integer|min:1|max:100000',
        ];
    }

    public function messages()
    {
        return [
            'model.string' => 'Название/Номер модели должно быть строкой',
            'model.max' => 'Слишком длинное название',
            'quantity.integer' => 'Поле колличество должно быть числом !',
            'quantity.min' => 'Минимальное колличество должно быть не меньше нуля',
            'quantity_from.min' => 'Минимальное колличество должно быть не меньше нуля',
            'quantity_from.max' => 'Слишком большое колличество',
            'quantity_to.min' => 'Минимальное колличество должно быть не меньше 1',
            'quantity_to.max' => 'Слишком большое колличество',
            'materials.string' => 'Поле материалы должно быть строкой',
            'materials.max' => 'Слишком длинный запрос',
            'colors.string' => 'Поле цвета должно быть строкой',
            'colors.max' => 'Слишком длинный запрос',
        ];
    }
}
