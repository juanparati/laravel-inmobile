<?php

namespace Juanparati\InMobile\Models\Concerns;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\InMobile\Enums\EnumerableParameter;
use Juanparati\InMobile\InMobile;

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
                $arr[$key] = $attr->format(InMobile::DEFAULT_DATE_FORMAT);
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
