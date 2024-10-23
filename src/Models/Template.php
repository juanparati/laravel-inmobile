<?php

namespace Juanparati\InMobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\InMobile\InMobile;
use Juanparati\InMobile\Models\Concerns\HasCallableAttributes;
use Juanparati\InMobile\Models\Concerns\HasSubmodels;

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
     */
    protected array $model = [
        'id'           => null,
        'name'         => null,
        'text'         => null,
        'senderName'   => null,
        'placeholders' => [],
        'created'      => null,
        'lastUpdated'  => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);

        if ($this->model['created']) {
            $this->model['created'] = now()->parse($this->model['created'])->timezone(InMobile::DEFAULT_TIMEZONE);
        }

        if ($this->model['lastUpdated']) {
            $this->model['lastUpdated'] = now()->parse($this->model['lastUpdated'])->timezone(InMobile::DEFAULT_TIMEZONE);
        }
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return static::recursiveToArray($this->model);
    }
}
