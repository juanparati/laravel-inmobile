<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;

abstract class MessageBase implements Arrayable
{
    use HasCallableAttributes;

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
        'to' => null,
        'countryHint' => null,
        'messageId' => null,
        'respectBlacklist' => true,
        'validityPeriodInSeconds' => 90,
        'statusCallbackUrl' => null,
        'sendTime' => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);
        $this->model['messageId'] = $this->model['messageId'] ?: uniqid('api_');
        $this->model['sendTime'] = $this->model['sendTime']
            ? now()->parse($this->model['sendTime'])->setTimezone(static::DEFAULT_TIMEZONE)->toImmutable()
            : now()->setTimezone(static::DEFAULT_TIMEZONE)->toImmutable();
    }

    /**
     * Set destination phone.
     *
     * @return $this
     */
    public function setTo(string|int $to): static
    {
        $this->model['to'] = (string) $to;

        return $this;
    }

    /**
     * Set destination phone.
     *
     * @return $this
     */
    public function setCountryHint(string|int $code): static
    {
        $this->model['countryHint'] = (string) $code;

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
    public function setSendTime(CarbonInterface $dateTime): static
    {
        $this->model['sendTime'] = $dateTime;

        return $this;
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return $this->model;
    }
}
