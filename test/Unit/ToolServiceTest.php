<?php

namespace Juanparati\InMobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\InMobile\Models\PhoneParse;

class ToolServiceTest extends InMobileTestBase
{
    /**
     * Test phone number parsing.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
     */
    public function testParseNumber()
    {
        $mockedResponse = json_decode(static::loadMockedResponse('parsedPhoneResponse.json'), true);
        $code           = $mockedResponse['results'][0]['countryCode'];
        $phone          = $mockedResponse['results'][0]['phoneNumber'];

        Http::fake([
            'tools/parsephonenumbers' => Http::response($mockedResponse),
        ]);

        $resp = $this->api()
            ->tool()
            ->phoneParse([PhoneParse::make('+'.$code, "+$code $phone")]);

        $this->assertNotEmpty($resp);
        $this->assertEquals($mockedResponse['results'][0]['msisdn'], $resp[0]->getMsisdn());
    }
}
