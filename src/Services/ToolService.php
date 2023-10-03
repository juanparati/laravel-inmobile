<?php

namespace Juanparati\Inmobile\Services;

use Juanparati\Inmobile\Models\PhoneParse;

class ToolService extends InmobileServiceBase
{
    /**
     * Parse and check phone numbers.
     *
     * @param  PhoneParse[]  $numbers
     * @return PhoneParse[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function phoneParse(array $numbers): array
    {
        $resp = $this->api->performRequest(
            'tools/parsephonenumbers',
            'POST',
            ['numbersToParse' => array_map(fn ($r) => $r->asPostData(), $numbers)]
        );

        return empty($resp['results']) ? [] : array_map(fn ($r) => new PhoneParse($r), $resp['results']);
    }
}
