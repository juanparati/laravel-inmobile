<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

final class EmailResponse implements Arrayable
{
    use HasCallableAttributes, HasSubmodels;

    /**
     * Default model.
     */
    protected array $model = [
        'messageId'              => null,
        'to'                     => [],
        'usedPlaceholderKeys'    => [],
        'notUsedPlaceholderKeys' => [],
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model)
    {
        $this->model       = array_merge($this->model, $model);
        $this->model['to'] = array_map(
            fn ($r) => new EmailRecipient($r['emailAddress'], $r['displayName']),
            $this->model['to']
        );
    }

    /**
     * Return model as array.
     */
    public function toArray(): array
    {
        return self::recursiveToArray($this->model);
    }
}
