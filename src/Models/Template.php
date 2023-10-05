<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

/**
 * Template model.
 *
 * @method string getId()
 * @method string getName()
 * @method string getText()
 * @method string getSenderName()
 * @method string getPlaceholders()
 * @method string getCreated()
 * @method string getLastUpdated()
 */
class Template implements Arrayable
{

    use HasCallableAttributes, HasSubmodels;


    /**
     * Default model.
     *
     * @var array
     */
    protected array $model = [
        'id'           => null,
        'name'         => null,
        'text'         => null,
        'senderName'   => null,
        'placeholders' => [],
        'created'      => null,
        'lastUpdated'  => null
    ];


    /**
     * Constructor.
     *
     * @param array $model
     */
    public function __construct(array $model = []) {
        $this->model = array_merge($this->model, $model);

        if ($this->model['created'])
            $this->model['created'] = now()->parse($this->model['created'])->timezone(Inmobile::DEFAULT_TIMEZONE);

        if ($this->model['lastUpdated'])
            $this->model['lastUpdated'] = now()->parse($this->model['lastUpdated'])->timezone(Inmobile::DEFAULT_TIMEZONE);
    }


    /**
     * Retrieve model as array.
     *
     * @return array
     */
    public function toArray():array
    {
        return static::recursiveToArray($this->model);
    }
}
