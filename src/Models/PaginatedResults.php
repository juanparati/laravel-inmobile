<?php

namespace Juanparati\InMobile\Models;

use Illuminate\Support\Str;
use Juanparati\InMobile\InMobile;
use Juanparati\InMobile\Models\Contracts\PostModel;

class PaginatedResults extends \NoRewindIterator
{
    /**
     * Current row relative to the page.
     */
    protected int $currentRelativeRow = 0;

    /**
     * Current absolute row.
     */
    protected int $currentRow = 0;

    /**
     * Constructor.
     */
    public function __construct(
        protected InMobile $api,
        protected array $result,
        protected ?string $modelType = null
    ) {
        parent::__construct((new \ArrayObject($result))->getIterator());
    }

    /**
     * Indicates if the page is the last.
     *
     * @return mixed|true
     */
    public function isLastPage()
    {
        return $this->result['_links']['isLastPage'] ?? true;
    }

    /**
     * Return current page.
     */
    public function current(): array|PostModel
    {
        $model = $this->result['entries'][$this->currentRelativeRow];

        if ($this->modelType) {
            /**
             * @var $modelType PostModel|null
             */
            $modelType = $this->modelType;
            $model     = new $modelType($model);
        }

        return $model;
    }

    /**
     * Move to next page.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function next(): void
    {
        if (! isset($this->result['entries'][++$this->currentRelativeRow])) {
            if (! $this->isLastPage()) {
                $this->result = $this->api->performRequest(
                    Str::replaceFirst('/'.$this->api->getVersion().'/', '', $this->result['_links']['next']),
                    'GET'
                );

                $this->currentRelativeRow = 0;
            }
        }

        $this->currentRow++;
    }

    public function key(): int
    {
        return $this->currentRow;
    }

    public function valid(): bool
    {
        return isset($this->result['entries'][$this->currentRelativeRow]) || ! $this->isLastPage();
    }
}
