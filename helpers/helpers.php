<?php

use Illuminate\Support\Str;

if (!function_exists('generateApiToken')) {
    function generateApiToken(): string
    {
        return Str::toBase64(Str::random(32));
    }
}
