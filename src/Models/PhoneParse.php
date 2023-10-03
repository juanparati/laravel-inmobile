<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Helpers\PhoneCodeHelper;
use Juanparati\Inmobile\Models\Contracts\PostModel;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;

/**
 * Phone parse model.
 *
 * @method string|null getCountryHint()
 * @method string|null getRawMsisdn()
 * @method string|null getCountryCode()
 * @method string|null getPhoneNumber()
 * @method string|null getMsisdn()
 * @method bool getIsValidMsisdn()
 */
class PhoneParse implements Arrayable, PostModel
{
    use HasCallableAttributes;

    /**
     * Default model.
     *
     * @var array|null[]
     */
    protected array $model = [
        'countryHint'   => null,
        'rawMsisdn'     => null,
        'countryCode'   => null,
        'phoneNumber'   => null,
        'msisdn'        => null,
        'isValidMsisdn' => false,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);

        if ($this->model['countryHint']) {
            $this->setCountryHint($this->model['countryHint']);
        }
    }

    /**
     * Factory method.
     */
    public static function make(string|int $code, string|int $rawMsisdn): static
    {
        return new static([
            'countryHint' => $code,
            'rawMsisdn'   => $rawMsisdn,
        ]);
    }

    /**
     * Set the country hint.
     *
     * @return $this
     */
    public function setCountryHint(string|int $code): static
    {
        $this->model['countryHint'] = PhoneCodeHelper::sanitize($code);

        return $this;
    }

    /**
     * Set raw MSISDN.
     *
     * @return $this
     */
    public function setRawMsisdn(string|int $rawMsisdn): static
    {
        $this->model['rawMsisdn'] = (string)$rawMsisdn;

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
     *
     * @return array|null[]
     */
    public function asPostData(): array
    {
        return [
            'countryHint' => $this->model['countryHint'],
            'rawMsisdn'   => $this->model['rawMsisdn'],
        ];
    }
}
