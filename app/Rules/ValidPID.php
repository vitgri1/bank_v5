<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPID implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $id = $value;
        $control_sum = 0;
        foreach (array_slice((str_split($id)), 0, 9) as $index => $num){
            $control_sum += ($index + 1) * (int) $num;
        }
        $control_sum += (int) substr($id, 9 , 1);

        if ($control_sum % 11 !== 10){
            $control_coef = $control_sum % 11;
        } else {
            $control_sum = 0;
            foreach (array_slice((str_split($id)), 0, 7) as $index => $num){
                $control_sum += ($index + 3) * (int) $num;
            }
            foreach (array_slice((str_split($id)), 7, 3) as $index => $num){
                $control_sum += ($index + 1) * (int) $num;
            }
            if ($control_sum % 11 !== 10){
                $control_coef = $control_sum % 11;
            } else {
                $control_coef = 0;
            }
        }

        if((int) substr($id, 10 , 1) !== $control_coef) {
            $fail('Personal ID is not valid');
        }
    }
}
