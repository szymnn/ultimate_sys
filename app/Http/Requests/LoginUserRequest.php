<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class LoginUserRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
                [
                    'success'   => false,
                    'message'   => 'Błędy walidacji:',
                    'data'      => $validator->errors()
                ]
        ));
    }
    public function messages()
    {
        return [
            'email.required'    => 'Email jest wymagany',
            'email.email'       => 'Email jest nieprawidłowy',
            'password.required' => 'Hasło jest wymagane',
            'password.string'   => 'Hasło jest nieprawidłowe',
            'password.min'      => 'Minimalna długość hasła to :min',
            'password.max'      => 'Maksymalna długość hasła to :max'
        ];
    }
}
