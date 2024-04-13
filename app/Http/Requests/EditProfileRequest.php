<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
            'last_name' => ['required', 'string', 'max:20'],
			'first_name' => ['required', 'string', 'max:20'],
			'last_name_kana' => ['required', 'string', 'max:20'],
			'first_name_kana' => ['required', 'string', 'max:20'],
			'year_of_birth' => ['required', 'integer'],
			'month_of_birth' => ['required', 'integer'],
			'date_of_birth' => ['required', 'integer'],
			'age' => ['required', 'string'],
			'sex' => ['required', 'string'],
			'zip' => ['required', 'string', 'max:7'],
			'prefecture' => ['required', 'string'],
			'municipalities' => ['required', 'string', 'max:50'],
			'other_address' => [ 'nullable', 'string', 'max:50'],
			'tel' => ['required', 'string', 'max:11'],
			'school_type' => ['required', 'string'],
			'school_name' => ['required', 'string', 'max:50'],
			'faculty_and_department' => ['required', 'string', 'max:50'],
			'literature_or_science' => ['required', 'string'],
			'enrollment_year' => ['required', 'integer'],
			'graduation_year' => ['required', 'integer'],
			'graduation_type' => ['required', 'string'],
			'job_change_experience' => ['required', 'string'],
			'employment_status' => ['required', 'string'],
			'latest_annual_income' => ['required', 'string'],
            'id_photo' => [ 'nullable', 'max:7340032', 'mimes:jpg,png,gif,heic'],
        ];
    }
}
