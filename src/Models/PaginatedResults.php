<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Support\Str;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;

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
        protected Inmobile $api,
        protected array $result,
        protected ?string $modelType = null
    ) {
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
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
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
