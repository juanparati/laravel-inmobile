<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

class RecipientList implements Arrayable, PostModel
{
    use HasSubmodels;

    /**
     * Default model.
     */
    protected array $model = [
        'id'      => null,
        'name'    => null,
        'created' => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model            = array_merge($this->model, $model);
        $this->model['created'] = $this->model['created']
            ? now()->parse($this->model['created'])->setTimezone(Inmobile::DEFAULT_TIMEZONE)->toImmutable()
            : now()->setTimezone(Inmobile::DEFAULT_TIMEZONE)->toImmutable();
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
        return static::recursiveToArray($this->model);
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        return ['name' => $this->model['name']];
    }
}
