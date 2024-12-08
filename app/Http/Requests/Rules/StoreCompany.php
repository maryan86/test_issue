<?php

namespace App\Http\Requests\Rules;

class StoreCompany
{
    public static function rules(): array
    {
        return [
            'title' => 'string|required',
            'phone' => 'required|string|regex:/[0-9\-()\+\s]{7,16}/',
            'description' => 'string|nullable',
        ];
    }
}
