<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Contracts\Validation\Validator;

class RefreshTokenRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
  
    public function rules()
    {
        return [
            'refresh_token'  => 'required',
            'client_id'      => 'required',
            'client_secret'  => 'required'
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json($validator->errors(), config('constant.STATUS_CODE.statusForbidden')));

    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}