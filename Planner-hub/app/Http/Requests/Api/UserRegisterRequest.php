<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class UserRegisterRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
  
    public function rules()
    {
        return [
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'password'       => 'required|min:8',
            'c_password'     => 'required|same:password',
            'autologin'      => 'required'
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
            'name.string'       => 'The name field must be string.',
            'name.max'          => 'The name field must be at most 255 characters',
            'email.email'       => 'Invalid email address',
            'email.unique'      => 'The email has already been taken.',
            'password.min'      => 'The password must be atleast 8 digits long.',
            'c_password.same'   => 'The c password and password must match.',
        ];
    }
}