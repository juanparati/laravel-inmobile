<?php

namespace Juanparati\Inmobile\Test\Unit;

use Juanparati\Inmobile\Models\PhoneParse;

class ToolServiceTest extends InmobileTestBase
{
    public function testParseNumber()
    {
        $resp = $this->api()
            ->tool()
            ->phoneParse([PhoneParse::make('+45', '+45 12345678')]);

        $this->assertNotEmpty($resp);
        $this->assertEquals('4512345678', $resp[0]->getMsisdn());
    }
}
