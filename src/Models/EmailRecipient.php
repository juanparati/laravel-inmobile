<?php

namespace Juanparati\InMobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\InMobile\Models\Contracts\PostModel;

/**
 * Model for e-mail recipient.
 */
final class EmailRecipient implements Arrayable, PostModel
{
    /**
     * Default model.
     */
    protected array $model = [
        'emailAddress' => '',
        'displayName'  => null,
    ];

    /**
     * Constructor.
     */
    public function __construct(string $emailAddress, ?string $displayName)
    {
        $this->model = [
            'emailAddress' => $emailAddress,
            'displayName'  => $displayName,
        ];
    }

    /**
     * Factory method.
     */
    public static function make(string $emailAddress, ?string $displayName = null): static
    {
        return new self($emailAddress, $displayName);
    }

    /**
     * Get email address.
     */
    public function getEmailAddress(): string
    {
        return $this->model['emailAddress'];
    }

    /**
     * Get display name.
     */
    public function getDisplayName(): ?string
    {
        return $this->model['displayName'];
    }

    /**
     * Set email address.
     *
     * @return $this
     */
    public function setEmailAddress(string $emailAddress): static
    {
        $this->model['emailAddress'] = $emailAddress;

        return $this;
    }

    /**
     * Set display name.
     *
     * @return $this
     */
    public function setDisplayName(?string $displayName): static
    {
        $this->model['displayName'] = $displayName;

        return $this;
    }

    /**
     * Obtain raw model.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->model;
    }

    /**
     * Obtain model for post request.
     */
    public function asPostData(): array
    {
        return $this->toArray();
    }
}
