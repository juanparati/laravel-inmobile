<?php

namespace Juanparati\Inmobile\Test\Unit;

use Illuminate\Support\Facades\Http;

class GdprServiceTest extends InmobileTestBase
{

    public function testDeletionRequest() {

        $mockedResponse = ['id' => 'cf97f715-63d4-41df-92a1-34eb87b866b5'];

        Http::fake([
            'sms/gdpr/deletionrequests' => Http::response($mockedResponse)
        ]);

        $resp = $this->api()
            ->gdpr()
            ->create('45', '12345678');

        $this->assertEquals($mockedResponse, $resp);
    }

}
