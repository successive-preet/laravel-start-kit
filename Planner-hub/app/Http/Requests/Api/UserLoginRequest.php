<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class UserLoginRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
  
    public function rules()
    {
        return [
            'email'          => 'required|email',
            'password'       => 'required|min:8'
        ];
    }

    public function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(response()->json($validator->errors(), config('constant.STATUS_CODE.statusForbidden')));

    }

    public function messages()
    {
        return [
            'required'          => 'The :attribute field is required.',
            'email.email'       => 'Invalid email address',
            'password.digits'   => 'The password must be 8 digits.',
        ];
    }
}