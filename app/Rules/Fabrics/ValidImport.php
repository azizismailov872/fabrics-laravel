<?php

namespace App\Rules\Fabrics;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidImport implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {   
        if(!in_array(strtolower($value),['import','export'])) {
            $fail('Выберите корректный тип - ввоз или вывоз');
        }
    }
}
