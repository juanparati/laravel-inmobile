<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Juanparati\Inmobile\Models\Contracts\PostModel;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;

/**
 * Model for e-mail message templates.
 *
 * @method EmailRecipient|null getFrom()
 * @method EmailRecipient[] getTo()
 * @method EmailRecipient[] getReplyTo()
 * @method string|null getMessageId()
 * @method CarbonInterface|null getSendTime()
 * @method bool getTracking()
 * @method string|null templateIdget(),
 * @method array getPlaceholders()
 */
class EmailMessageTemplate extends EmailMessageBase implements PostModel
{
    use HasCallableAttributes;

    /**
     * Default model.
     *
     * @var array
     */
    protected array $model = [
        'from'         => null,
        'to'           => [],
        'replyTo'      => [],
        'messageId'    => null,
        'sendTime'     => null,
        'tracking'     => false,
        'templateId'   => null,
        'placeholders' => [],
    ];


    /**
     * Factory method.
     *
     * @param EmailRecipient $from
     * @param array $to
     * @param string $templateId
     * @return $this
     */
    public static function make(EmailRecipient $from, array $to, string $templateId): static
    {
        return new static([
            'from'       => $from,
            'to'         => $to,
            'templateId' => $templateId,
        ]);
    }


    /**
     * Add placeholder.
     *
     * @param string $key
     * @param string|int|float $value
     * @return $this
     */
    public function addPlaceholder(string $key, string|int|float $value) : static {
        $this->model['placeholders'][] = [$key, $value];
        return $this;
    }


    /**
     * Set placeholder.
     *
     * @param array $placeholders
     * @return $this
     */
    public function setPlaceholders(array $placeholders): static {
        $this->model['placeholders'] = $placeholders;
        return $this;
    }
}
