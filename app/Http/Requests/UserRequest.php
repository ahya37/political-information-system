<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required',
            'gender' => 'required|in:0,1',
            'place_berth' => 'required',
            'date_berth' => 'required',
            'blood_group' => 'required|in:A,B,AB,O',
            'marital_status' => 'required',
            'job_id' => 'required',
            'religion' => 'required',
            'nik' => 'required',
            'education_id' => 'required',
            'phone_number' => 'required',
            'village_id' => 'required',
            'whatsapp' => 'required',
            'address' => 'required',
            'photo' => 'required|mimes:png,jpg,jpeg',
            'ktp' => 'required|mimes:png,jpg,jpeg',
        ];
    }
}
