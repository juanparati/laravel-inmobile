<?php

namespace Juanparati\Inmobile\Models;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;
use Juanparati\Inmobile\Models\Extensions\HasCallableAttributes;
use Juanparati\Inmobile\Models\Extensions\HasSubmodels;

/**
 * Base model for e-mail messages.
 *
 * @method EmailRecipient|null getFrom()
 * @method EmailRecipient[] getTo()
 * @method EmailRecipient[] getReplyTo()
 * @method string|null getMessageId()
 * @method CarbonInterface|null getSendTime()
 * @method bool getTracking()
 */
abstract class EmailMessageBase implements Arrayable, PostModel
{
    use HasCallableAttributes, HasSubmodels;

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
    ];

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(array $model = [])
    {
        $this->model = array_merge($this->model, $model);
        $this->setSendTime($this->model['sendTime'] ?: now());
        $this->setFrom($this->model['from'] ?: null);
        $this->setTo($this->model['to'] ?: []);
        $this->setReplyTo($this->model['replyTo'] ?: []);
    }

    /**
     * Set send time.
     *
     * @return $this
     */
    public function setSendTime(CarbonInterface|string|\DateTime $sendTime): static
    {
        $this->model['sendTime'] = now()
            ->parse($sendTime)
            ->setTimezone(Inmobile::DEFAULT_TIMEZONE)
            ->toImmutable();

        return $this;
    }

    /**
     * Set from.
     *
     * @param  EmailRecipient  $recipient
     * @return $this
     */
    public function setFrom(EmailRecipient|array $recipient): static
    {
        if (is_array($recipient)) {
            $recipient = EmailRecipient::make($recipient['emailAddress'], $recipient['displayName']);
        }

        $this->model['from'] = $recipient;

        return $this;
    }

    /**
     * Add recipient to destination.
     *
     * @return $this
     */
    public function addTo(EmailRecipient $recipient): static
    {
        $this->model['to'][] = $recipient;

        return $this;
    }

    /**
     * Set destination.
     *
     * @param  EmailRecipient[]  $recipients
     * @return $this
     */
    public function setTo(array $recipients): static
    {

        $this->model['to'] = [];

        foreach ($recipients as $recipient) {
            if (is_array($recipient)) {
                $recipient = EmailRecipient::make($recipient['emailAddress'], $recipient['displayName']);
            }

            $this->addTo($recipient);
        }

        return $this;
    }

    /**
     * Add reply to.
     *
     * @return $this
     */
    public function addReplyTo(EmailRecipient $recipient): static
    {
        $this->model['replyTo'][] = $recipient;

        return $this;
    }

    /**
     * Set reply to.
     *
     * @return $this'
     */
    public function setReplyTo(array $recipients): static
    {
        $this->model['replyTo'] = [];

        foreach ($recipients as $recipient) {
            if (is_array($recipient)) {
                $recipient = EmailRecipient::make($recipient['emailAddress'], $recipient['displayName']);
            }

            $this->addReplyTo($recipient);
        }

        return $this;
    }

    /**
     * Set tracking state.
     *
     * @return $this
     */
    public function setTracking(bool $state): static
    {
        $this->model['tracking'] = $state;

        return $this;
    }

    /**
     * Retrieve model as array.
     */
    public function toArray(): array
    {
        return static::recursiveToArray($this->model);
    }

    /**
     * Retrieve for post request.
     */
    public function asPostData(): array
    {
        return $this->toArray();
    }

}
