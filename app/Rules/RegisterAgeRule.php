<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RegisterAgeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value === 'null' || ( (int)$value > 19 && (int)$value < 30 ) );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '年齢は、20から29の間で指定してください。';
    }
}
