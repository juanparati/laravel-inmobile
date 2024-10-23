<?php

namespace Juanparati\InMobile\Models\Concerns;

use Illuminate\Support\Str;

trait HasCallableAttributes
{
    /**
     * Magic method that retrieve model properties.
     *
     * @return mixed|void
     */
    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $attr = lcfirst(substr($method, 3));

            if (array_key_exists($attr, $this->model)) {
                return $this->model[$attr];
            }
        }
    }
}
