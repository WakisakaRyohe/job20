<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditJobCareerRequest extends FormRequest
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
			'job_careers_company_name' => ['required', 'string', 'max:50'],
			'year_of_joining' => ['required', 'integer'],
			'month_of_joining' => ['required', 'integer'],
			'year_of_retirement' => ['required', 'integer'],
			'month_of_retirement' => ['required', 'integer'],
			'employment_status' => ['required', 'string'],
			'job_careers_industry' => ['required', 'string'],
			'number_of_employees' => ['nullable', 'string'],
			'department_or_post' => ['nullable', 'string', 'max:50'],
			'job_details' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
