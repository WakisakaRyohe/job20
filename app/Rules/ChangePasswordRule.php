<?php

namespace App\Rules;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class ChangePasswordRule implements Rule
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
        $password_old = Auth::user()->password;
        return (Hash::check($value, $password_old));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '古いパスワードが違います。';
    }
}
