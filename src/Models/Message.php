<?php

namespace Juanparati\Inmobile\Models;

use Juanparati\Inmobile\Enums\Encoding;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;

/**
 * Message model.
 *
 * @method string|null getTo()
 * @method string|null getCountryHint()
 * @method string|null getMessageId()
 * @method bool getRespectBlacklist()
 * @method int getValidityPeriodInSeconds()
 * @method string|null getStatusCallbackUrl()
 * @method string|null getSendTime()
 * @method string|null geText()
 * @method string|null getFrom()
 * @method bool getFlash()
 * @method Encoding getEncoding()
 */
class Message extends MessageBase implements PostModel
{
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
        'text'                    => null,
        'from'                    => null,
        'flash'                   => false,
        'encoding'                => Encoding::GSM7,
    ];

    /**
     * Factory method.
     *
     * @param string|int $code Destination country code
     * @param string|int $phone Destination phone (without country code)
     * @param string|int $from Sender
     * @param string $text Message
     */
    public static function make(
        string|int $code,
        string|int $phone,
        string|int $from,
        string     $text
    ): static
    {
        return new static([
            'to'          => $code . $phone,
            'countryHint' => $code,
            'from'        => $from,
            'text'        => $text,
        ]);
    }

    /**
     * Set text.
     *
     * @return $this
     */
    public function setText(string $text): static
    {
        $this->model['text'] = $text;

        return $this;
    }

    /**
     * Set from.
     *
     * @return $this
     */
    public function setFrom(string $from): static
    {
        $this->model['from'] = $from;

        return $this;
    }

    /**
     * Set flash state.
     *
     * @return $this
     */
    public function setFlash(bool $state): static
    {
        $this->model['flash'] = $state;

        return $this;
    }

    /**
     * Set encoding.
     *
     * @return $this
     */
    public function setEncoding(Encoding $encoding): static
    {
        $this->model['encoding'] = $encoding;

        return $this;
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        $model = $this->model;
        $model['sendTime'] = $model['sendTime']->format(Inmobile::DEFAULT_DATE_FORMAT);
        $model['encoding'] = $model['encoding']->value;

        return $model;
    }
}
