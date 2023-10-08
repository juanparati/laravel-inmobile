<?php

namespace Juanparati\Inmobile\Helpers;

class PhoneCodeHelper
{
    public static function sanitize(string|int $code): string
    {
        return str_replace('+', '', $code);
    }
}
