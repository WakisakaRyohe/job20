<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ChangeEmailRule;
use App\Rules\ChangePasswordRule;
use App\Rules\RegisterAgeRule;
use App\Rules\Hankaku;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_new' => ['required', 'string', 'min:8', 'max:32', new Hankaku()],
        ];
    }
}
