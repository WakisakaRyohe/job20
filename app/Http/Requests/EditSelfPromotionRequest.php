<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSelfPromotionRequest extends FormRequest
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
			'self_promotion' => ['nullable', 'string', 'max:800'],
			'motivation' => ['nullable', 'string', 'max:800'],
        ];
    }
}
