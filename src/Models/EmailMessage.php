<?php

namespace Juanparati\InMobile\Models;

use Carbon\CarbonInterface;
use Juanparati\InMobile\Models\Contracts\PostModel;

/**
 * Model for e-mail messages.
 *
 * @method EmailRecipient|null getFrom()
 * @method EmailRecipient[] getTo()
 * @method EmailRecipient[] getReplyTo()
 * @method string|null getMessageId()
 * @method CarbonInterface|null getSendTime()
 * @method bool getTracking()
 * @method string getSubject()
 * @method string getHtml()
 * @method string|null getText()
 */
class EmailMessage extends EmailMessageBase implements PostModel
{
    /**
     * Default model.
     */
    protected array $model = [
        'from'      => null,
        'to'        => [],
        'replyTo'   => [],
        'messageId' => null,
        'sendTime'  => null,
        'tracking'  => false,
        'subject'   => '',
        'html'      => '',
        'text'      => null,
    ];

    /**
     * Factory method.
     *
     * @return $this
     */
    public static function make(EmailRecipient $from, array $to, string $subject, string $html): static
    {
        return new static([
            'from'    => $from,
            'to'      => $to,
            'subject' => $subject,
            'html'    => $html,
        ]);
    }
}
