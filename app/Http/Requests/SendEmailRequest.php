<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            'email' => ['required', 'string', 'email:filter,dns', 'max:50'],
        ];
        // 「exists:users,email」で、usersテーブルのemailカラムに存在するか確認できるが、
        // 第３者に登録メールアドレスがわかるので使わない
    }
}
