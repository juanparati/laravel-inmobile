<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Juanparati\Inmobile\Helpers\PhoneCodeHelper;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

class Recipient implements Arrayable, PostModel
{
    use HasSubmodels;

    /**
     * Default model.
     */
    protected array $model = [
        'externalCreated' => null,
        'numberInfo'      => [
            'countryCode' => null,
            'phoneNumber' => null,
        ],
        'fields'          => [
            'firstname'   => null,
            'lastname'    => null,
            'birthday'    => null,
            'custom1'     => null,
            'custom2'     => null,
            'custom3'     => null,
            'custom4'     => null,
            'custom5'     => null,
            'custom6'     => null,
            'email'       => null,
            'zipCode'     => null,
            'address'     => null,
            'companyName' => null,
        ],
        'id'              => null,
        'listId'          => null,
        'created'         => null,
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

        $this->setCreatedAt($this->model['externalCreated'] ?: now());
    }

    /**
     * Factory method.
     */
    public static function make(string|int $code, string|int $phone): static
    {
        return new static([
            'numberInfo' => [
                'countryCode' => (string) $code,
                'phoneNumber' => (string) $phone,
            ],
        ]);
    }

    /**
     * Set country code/international phone prefix.
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
     * Get phone code/international phone prefix.
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
     * Set a field value.
     *
     * @return $this
     */
    public function addField(string $field, string|int|float $value): static
    {
        if (! array_key_exists($field, $this->model['fields'])) {
            throw new \RuntimeException("$field is not allowed");
        }

        $this->model['fields'][$field] = (string) $value;

        return $this;
    }

    /**
     * Get fields or field data.
     *
     * @return mixed|null
     */
    public function getField(string $field = null)
    {
        if ($field === null) {
            return $this->model['fields'];
        }

        return $this->model['fields'][$field] ?? null;
    }

    /**
     * Set externalCreated.
     *
     * @return $this
     */
    public function setCreatedAt(string|CarbonInterface|\DateTime $dateTime): static
    {
        $this->model['externalCreated'] = now()
            ->parse($dateTime)
            ->setTimezone(Inmobile::DEFAULT_TIMEZONE)
            ->toImmutable();

        return $this;
    }

    /**
     * Get externalCreated.
     */
    public function getCreatedAt(): CarbonInterface
    {
        return $this->model['externalCreated'];
    }

    /**
     * Get recipient Id.
     */
    public function getId(): ?string
    {
        return $this->model['id'];
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return static::recursiveToArray($this->model);
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        $model = $this->toArray();

        Arr::forget($model, 'id');
        Arr::forget($model, 'listId');
        Arr::forget($model, 'created');

        return $model;
    }
}
