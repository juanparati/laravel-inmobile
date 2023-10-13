<?php

namespace Juanparati\Inmobile\Models\Extensions;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Enums\EnumerableParameter;
use Juanparati\Inmobile\Inmobile;

trait HasSubmodels
{
    /**
     * Convert array into a model in recursive way.
     */
    protected static function recursiveToArray(array $model): array
    {
        $arr = [];

        foreach ($model as $key => $attr) {
            if ($attr instanceof Arrayable) {
                $arr[$key] = $attr->toArray();
            } elseif ($attr instanceof CarbonInterface) {
                $arr[$key] = $attr->format(Inmobile::DEFAULT_DATE_FORMAT);
            } elseif (is_array($attr)) {
                $arr[$key] = static::recursiveToArray($attr);
            } elseif (is_null($attr)) {
                $arr[$key] = null;
            } elseif ($attr instanceof EnumerableParameter) {
                $arr[$key] = $attr->asString();
            } else {
                $arr[$key] = $attr;
            }
        }

        return $arr;
    }

}
