<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Helpers\PhoneCodeHelper;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

abstract class MessageBase implements Arrayable
{
    use HasCallableAttributes, HasSubmodels;

    /**
     * Default model.
     */
    protected array $model = [
        'to'                      => null,
        'countryHint'             => null,
        'messageId'               => null,
        'respectBlacklist'        => true,
        'validityPeriodInSeconds' => 90,
        'statusCallbackUrl'       => null,
        'sendTime'                => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model              = array_merge($this->model, $model);
        $this->model['messageId'] = $this->model['messageId'] ?: uniqid('api_');

        if ($this->model['to']) {
            $this->setTo($this->model['to']);
        }

        if ($this->model['countryHint']) {
            $this->setCountryHint($this->model['countryHint']);
        }

        $this->setSendTime($this->model['sendTime'] ?: now());
    }

    /**
     * Set destination phone.
     *
     * @return $this
     */
    public function setTo(string|int $to): static
    {
        $this->model['to'] = PhoneCodeHelper::sanitize($to);

        return $this;
    }

    /**
     * Set destination phone.
     *
     * @return $this
     */
    public function setCountryHint(string|int $code): static
    {
        $this->model['countryHint'] = PhoneCodeHelper::sanitize($code);

        return $this;
    }

    /**
     * Set destination phone.
     *
     * @return $this
     */
    public function setMessageId(string|int $id): static
    {
        $this->model['messageId'] = (string) $id;

        return $this;
    }

    /**
     * Set blacklist respect state.
     *
     * @return $this
     */
    public function setRespectBlacklist(bool $state): static
    {
        $this->model['respectBlacklist'] = $state;

        return $this;
    }

    /**
     * Set validity period.
     *
     * @return $this
     */
    public function setValidityPeriod(int $seconds): static
    {
        $this->model['validityPeriodInSeconds'] = $seconds;

        return $this;
    }

    /**
     * Set status callback url.
     *
     * @return $this
     */
    public function setStatusCallbackUrl(?string $url): static
    {
        $this->model['statusCallbackUrl'] = $url;

        return $this;
    }

    /**
     * Set send time.
     *
     * @return $this
     */
    public function setSendTime(CarbonInterface|string|\DateTime $dateTime): static
    {
        $this->model['sendTime'] = now()
            ->parse($dateTime)
            ->setTimezone(Inmobile::DEFAULT_TIMEZONE)
            ->toImmutable();

        return $this;
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return static::recursiveToArray($this->model);
    }
}
