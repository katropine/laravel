<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class E164Phone implements Rule
{
    
    public function passes($attribute, $value): bool
    {
        return preg_match('/^\+61\d{9}$|^\+64\d{8,9}$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute must be a valid Australian or New Zealand E.164 phone number.';
    }
}
