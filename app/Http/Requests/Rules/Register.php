<?php

namespace App\Http\Requests\Rules;

class Register
{
    public static function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string|regex:/[0-9\-()\+\s]{7,16}/',
        ];
    }
}
