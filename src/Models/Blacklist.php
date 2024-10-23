<?php

namespace Juanparati\InMobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Juanparati\InMobile\Helpers\PhoneCodeHelper;
use Juanparati\InMobile\InMobile;
use Juanparati\InMobile\Models\Contracts\PostModel;

class Blacklist implements Arrayable, PostModel
{
    /**
     * Default model.
     */
    protected array $model = [
        'numberInfo'            => [
            'countryCode' => null,
            'phoneNumber' => null,
        ],
        'comment'               => null,
        'removeFromAllLists'    => false,
        'id'                    => null,
        'created'               => null,
        'reasonCode'            => null,
        'reasonCodeDescription' => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);

        if ($this->model['numberInfo']['countryCode']) {
            $this->setCode($this->model['numberInfo']['countryCode']);
        }

        $this->model['created'] = $this->model['created']
            ? now()->parse($this->model['created'])->setTimezone(InMobile::DEFAULT_TIMEZONE)->toImmutable()
            : now()->setTimezone(InMobile::DEFAULT_TIMEZONE)->toImmutable();
    }

    /**
     * Factory method.
     */
    public static function make(string|int $code, string|int $phone, bool $removeFromAllLists = false): static
    {
        return new static([
            'numberInfo'         => [
                'countryCode' => (string) $code,
                'phoneNumber' => (string) $phone,
            ],
            'removeFromAllLists' => $removeFromAllLists,
        ]);
    }

    /**
     * Set country code/internal phone code.
     *
     * @return $this
     */
    public function setCode(string|int $code): static
    {
        $this->model['numberInfo']['countryCode'] = PhoneCodeHelper::sanitize($code);

        return $this;
    }

    /**
     * Set phone number.
     *
     * @return $this
     */
    public function setPhone(string|int $phone): static
    {
        $this->model['numberInfo']['phoneNumber'] = (string) $phone;

        return $this;
    }

    /**
     * Set comment.
     *
     * @return $this
     */
    public function setComment(string $comment): static
    {
        $this->model['comment'] = $comment;

        return $this;
    }

    /**
     * Set "remove from lists" state.
     *
     * @return $this
     */
    public function setRemoveFromAllLists(bool $state): static
    {
        $this->model['removeFromAllLists'] = $state;

        return $this;
    }

    /**
     * Get blacklist record id.
     */
    public function getId(): ?string
    {
        return $this->model['id'];
    }

    /**
     * Get code/international phone prefix.
     */
    public function getCode(): ?string
    {
        return $this->model['numberInfo']['countryCode'];
    }

    /**
     * Get phone number.
     */
    public function getPhone(): ?string
    {
        return $this->model['numberInfo']['phoneNumber'];
    }

    /**
     * Get comment.
     */
    public function getComment(): ?string
    {
        return $this->model['comment'];
    }

    public function getRemoveFromAllLists(): bool
    {
        return $this->model['removeFromAllLists'];
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return $this->model;
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        $model = $this->toArray();

        Arr::forget($model, 'reasonCode');
        Arr::forget($model, 'reasonCodeDescription');
        Arr::forget($model, 'created');
        Arr::forget($model, 'id');

        return $model;
    }
}
