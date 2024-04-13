<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditSkillRequest extends FormRequest
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
			'certifications' => ['nullable', 'distinct', 'max:1000'],
			'speaking_english_level' => ['nullable', 'string'],
			'reading_english_level' => ['nullable', 'string'],
			'writing_english_level' => ['nullable', 'string'],
			'toeic_score' => ['nullable', 'integer', 'digits_between:1,3'],
			'toefl_score' => ['nullable', 'integer', 'digits_between:1,3'],
			'others_language' => ['nullable', 'string'],
			'input_others_language' => ['nullable', 'string', 'max:50'],
			'reading_others_language_level' => ['nullable', 'string'],
			'speaking_others_language_level' => ['nullable', 'string'],
			'writing_others_language_level' => ['nullable', 'string'],
        ];
    }
}
