<?php

namespace Juanparati\InMobile\Enums;

enum Encoding: string implements EnumerableParameter
{
    case AUTO = 'auto';
    case GSM7 = 'gsm7';
    case UCS2 = 'ucs2';

    public function asString(): string
    {
        return $this->value;
    }
}
