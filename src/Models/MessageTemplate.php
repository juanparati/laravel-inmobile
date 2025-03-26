<?php

namespace Juanparati\InMobile\Models;

use Juanparati\InMobile\Models\Contracts\PostModel;

/**
 * Message template model.
 *
 * @method string|null getTo()
 * @method string|null getCountryHint()
 * @method string|null getMessageId()
 * @method bool getRespectBlacklist()
 * @method int getValidityPeriodInSeconds()
 * @method string|null getStatusCallbackUrl()
 * @method string|null getSendTime()
 * @method array getPlaceholders()
 */
class MessageTemplate extends MessageBase implements PostModel
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
        'placeholders'            => [],
    ];

    /**
     * Factory method.
     */
    public static function make(
        string|int $code,
        string|int $phone,
        string|int $from,
    ): static {
        return new static([
            'to'          => $code.$phone,
            'countryHint' => $code,
        ]);
    }

    /**
     * Add placeholder.
     *
     * @return $this
     */
    public function addPlaceholder(string $placeholder, string|int|float $value): static
    {
        $this->model['placeholders'][$placeholder] = $value;

        return $this;
    }

    /**
     * Retrieve model as post data.
     */
    public function asPostData(): array
    {
        return $this->toArray();
    }
}
