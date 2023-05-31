<?php

namespace App\Http\Requests\Fabrics\Logs;

use App\Rules\Fabrics\ValidImport;
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
            'quantity' => 'required|integer|min:1',
            'weight' => 'required|integer|min:0',
            'date_time' => 'required|date_format:Y-m-d H:i||before_or_equal:'.date(DATE_ATOM),
            'fabric_id' => 'required|exists:fabrics,id',
            'type' => ['required','string', new ValidImport],
            'message' => 'nullable|string|max:1000',
        ];
    }


    public function messages()
    {
        return [
            'quantity.required' => 'Введите колличество вывозимых/завозимых тканей',
            'quantity.integer' => 'Поле колличество должно быть числом',
            'quantity.min' => 'Минимальное колличство не может быть меньше 1',
            'weight.required' => 'Поле вес обязательно к заполнению',
            'weight.integer' => 'Введите корректный вес',
            'weight.min' => 'Вес не может быть меньше 0',
            'date_time.required' => 'Поле дата обязательно к заполнению',
            'date_time.date_format' => 'Введите корректные дату и время',
            'date_time.before_or_equal' => 'Вы не можете ввести дату и время больше нынешнего',
            'fabric_id.required' => 'Выберите модель тканей',
            'fabric_id.exists' => 'Выбранной вами модели не существует в базе данных',
            'type.required' => 'Введите тип ввоз или вывоз',
            'type.string' => 'Выберите корректный тип',
            'message.string' => 'Поле сообщение должно быть строкой',
            'message.max' => 'Вы превисили максимальное колличество слов',
        ];
    }
}
