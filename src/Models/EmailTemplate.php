<?php

namespace Juanparati\Inmobile\Models;

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
class EmailTemplate extends Template
{
    /**
     * Default model.
     *
     * @var array
     */
    protected array $model = [
        'id'           => null,
        'name'         => null,
        'html'         => null,
        'text'         => null,
        'subject'      => null,
        'preheader'    => null,
        'placeholders' => [],
        'created'      => null,
        'lastUpdated'  => null
    ];
}
