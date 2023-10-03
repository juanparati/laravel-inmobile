<?php

namespace Juanparati\Inmobile\Models;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Juanparati\Inmobile\Inmobile;
use Juanparati\Inmobile\Models\Contracts\PostModel;

class PaginatedResults extends \NoRewindIterator implements Arrayable
{
    /**
     * Current page.
     */
    protected int $pageNum = 1;

    /**
     * Constructor.
     *
     * @param  int  $limit
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
     * Return entries.
     */
    public function toArray(): array
    {
        /**
         * @var $modelType PostModel|null
         */
        $modelType = $this->modelType;

        return empty($this->result['entries'])
            ? [] : array_map(fn ($r) => $modelType ? new $modelType($r) : $r, $this->result['entries']);
    }

    /**
     * Return current page.
     */
    public function current(): array
    {
        return $this->toArray();
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
        if ($this->isLastPage()) {
            throw new \RuntimeException('No more pages');
        }

        $this->result = $this->api->performRequest(
            Str::replaceFirst('/'.$this->api->getVersion().'/', '', $this->result['_links']['next']),
            'GET'
        );

        $this->pageNum++;
    }

    public function key(): int
    {
        return $this->pageNum;
    }

    public function valid(): bool
    {
        return ! $this->isLastPage();
    }
}
