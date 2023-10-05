<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Juanparati\Inmobile\Models\Contracts\PostModel;

/**
 * Model for e-mail recipient.
 */
final class EmailRecipient implements Arrayable, PostModel
{

    /**
     * Default model.
     *
     * @var array
     */
    protected array $model = [
        'emailAddress' => '',
        'displayName'  => null,
    ];


    /**
     * Constructor.
     *
     * @param string $emailAddress
     * @param string|null $displayName
     */
    public function __construct(string $emailAddress, ?string $displayName)
    {
        $this->model = [
            'emailAddress' => $emailAddress,
            'displayName'  => $displayName
        ];
    }


    /**
     * Factory method.
     *
     * @param string $emailAddress
     * @param string|null $displayName
     * @return static
     */
    public static function make(string $emailAddress, ?string $displayName = null): static
    {
        return new static($emailAddress, $displayName);
    }


    /**
     * Get email address.
     *
     * @return string
     */
    public function getEmailAddress(): string
    {
        return $this->model['emailAddress'];
    }


    /**
     * Get display name.
     *
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->model['displayName'];
    }


    /**
     * Set email address.
     *
     * @param string $emailAddress
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
     * @param string|null $displayName
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
     *
     * @return array
     */
    public function asPostData(): array
    {
        return $this->toArray();
    }
}
