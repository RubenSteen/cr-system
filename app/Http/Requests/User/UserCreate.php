<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class UserCreate extends FormRequest
{
    /**
     * Validates the request.
     *
     * @param array $data
     * @throws \Illuminate\Validation\ValidationException
     * @return array
     */
    public function validateData($data)
    {
        return Validator::make($data, $this->rules(), $this->messages(), $this->attributes())->validate();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string|unique:App\User,username',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:App\User,email',
            'admin' => 'required|boolean',
            'active' => 'required|boolean',
            'date_of_birth' => 'nullable|string|date',
            'street_name' => 'nullable|string',
            'house_number' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'city' => 'nullable|string',
            'province' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'comment' => 'nullable|string|max:200',
        ];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Custom message for validation.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Password rules which does not get used often in this application
     *
     * @return array
     */
    public function passwordRules()
    {
        return [
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ];
    }
}
