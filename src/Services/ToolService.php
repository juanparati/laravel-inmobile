<?php

namespace Juanparati\InMobile\Services;

use Juanparati\InMobile\Models\PhoneParse;

class ToolService extends InMobileServiceBase
{
    /**
     * Parse and check phone numbers.
     *
     * @param  PhoneParse[]  $numbers
     * @return PhoneParse[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
