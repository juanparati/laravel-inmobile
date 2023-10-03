<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Models\Contracts\PostModel;

class RecipientList implements Arrayable, PostModel
{
    /**
     * Default date format.
     */
    protected const DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * Default used timezone.
     */
    protected const DEFAULT_TIMEZONE = 'UTC';

    /**
     * Default model.
     */
    protected array $model = [
        'id' => null,
        'name' => null,
        'created' => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);
        $this->model['created'] = $this->model['created']
            ? now()->parse($this->model['created'])->setTimezone(static::DEFAULT_TIMEZONE)->toImmutable()
            : now()->setTimezone(static::DEFAULT_TIMEZONE)->toImmutable();
    }

    /**
     * Factory method.
     */
    public static function make(string $name): static
    {
        return new static(['name' => $name]);
    }

    /**
     * Get the list id.
     */
    public function getId(): ?string
    {
        return $this->model['id'];
    }

    /**
     * Get the created.
     */
    public function getCreatedAt(): ?CarbonInterface
    {
        return $this->model['created'];
    }

    /**
     * Get list name.
     */
    public function getName(): string
    {
        return $this->model['name'];
    }

    /**
     * Set name.
     *
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->model['name'] = $name;

        return $this;
    }

    /**
     * Retrieve model as array.
     *
     * @return array|null[]
     */
    public function toArray()
    {
        return $this->model;
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        return ['name' => $this->model['name']];
    }
}
