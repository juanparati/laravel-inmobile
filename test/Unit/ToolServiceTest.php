<?php

namespace Juanparati\Inmobile\Test\Unit;

use Juanparati\Inmobile\Models\PhoneParse;

class ToolServiceTest extends InmobileTestBase
{

    /**
     * Test phone number parsing.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testParseNumber()
    {
        $resp = $this->api()
            ->tool()
            ->phoneParse([PhoneParse::make('+45', '+45 12345678')]);

        $this->assertNotEmpty($resp);
        $this->assertEquals('4512345678', $resp[0]->getMsisdn());
    }
}
