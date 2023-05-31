<?php

namespace App\Http\Requests\Fabrics\Logs;

use App\Rules\Fabrics\ValidImport;
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
            'quantity' => 'sometimes|integer|min:0',
            'wieght' => 'sometimes|integer|min:0',
            'quantity_from' => 'sometimes|integer|min:0|max:100000',
            'quantity_to' => 'sometimes|integer|min:0|max:100000',
            'weight_from' => 'sometimes|integer|min:0|max:100000',
            'weight_to' => 'sometimes|integer|min:0|max:100000',
            'date_time' => 'sometimes|date_format:Y-m-d H:i|before_or_equal:'.date(DATE_ATOM),
            'date_from' => 'sometimes|date_format:Y-m-d H:i|before_or_equal:'.date(DATE_ATOM),
            'date_to' => 'sometimes|date_format:Y-m-d H:i||before_or_equal:'.date(DATE_ATOM),
            'fabric_id' => 'sometimes|integer|min:1',
            'user_id' => 'sometimes|integer|min:1',
            'type' => ['sometimes','string', new ValidImport],
        ];
    }
    public function messages()
    {
        return [
            'quantity.integer' => 'Поле колличество должно быть числом !',
            'quantity.min' => 'Минимальное колличество должно быть не меньше нуля',
            'quantity_from.min' => 'Минимальное колличество должно быть не меньше нуля',
            'quantity_from.max' => 'Слишком большое колличество',
            'quantity_to.min' => 'Минимальное колличество должно быть не меньше 1',
            'quantity_to.max' => 'Слишком большое колличество',
            'colors.string' => 'Поле цвета должно быть строкой',
            'colors.max' => 'Слишком длинный запрос',
            'date_format' => 'Выберите корректную дату',
            'before_or_equal' => 'Вы не можете ввести дату и время больше нынешнего',
            'fabric_id.integer' => 'Выберите модель ткани из списка',
            'fabric_id.min' => 'Выберите корректную модель ткани из списка',
            'user_id.integer' => 'Выберите пользователя из списка',
            'user_id.min' => 'Выберите корректного пользователя из списка',
            'type.string' => 'Выберите корректный тип - ввоз или вывоз',
            'weight.integer' => 'Поле вес должно быть числом !',
            'weight.min' => 'Минимальный вес должен быть не меньше нуля',
            'weight_from.min' => 'Минимальный вес должен быть не меньше нуля',
            'weight_from.max' => 'Слишком большой вес',
            'weight_to.min' => 'Минимальный вес должен быть не меньше 1',
            'weight_to.max' => 'Слишком большой вес',
        ];
    }
}
