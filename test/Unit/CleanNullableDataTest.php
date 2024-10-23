<?php

namespace Juanparati\InMobile\Test\Unit;

use Juanparati\InMobile\Models\Recipient;
use PHPUnit\Framework\TestCase;

class CleanNullableDataTest extends TestCase
{
    protected const TEST_PAYLOAD = [
        'externalCreated' => null,
        'numberInfo'      => [
            'countryCode' => null,
            'phoneNumber' => '12345678',
        ],
        'fields'          => [
            'firstname'   => null,
            'lastname'    => null,
            'birthday'    => '1945-04-31',
            'custom1'     => 'Custom1',
            'custom2'     => 'Custom2',
            'custom3'     => 'Custom3',
            'custom4'     => 'Custom4',
            'custom5'     => 'Custom5',
            'custom6'     => null,
            'email'       => 'test@test.dk',
            'zipCode'     => '8000',
            'address'     => 'testroad 12',
            'companyName' => 'test company',
        ],
    ];

    public function testCleanNullableData()
    {
        $data = Recipient::cleanNullableData(static::TEST_PAYLOAD);

        $this->assertArrayNotHasKey('externalCreated', $data);
        $this->assertArrayNotHasKey('countryCode', $data['numberInfo']);
        $this->assertNotEmpty($data['numberInfo']['phoneNumber']);
        $this->assertArrayNotHasKey('firstname', $data['fields']);
        $this->assertArrayNotHasKey('lastname', $data['fields']);
        $this->assertArrayNotHasKey('custom6', $data['fields']);
        $this->assertEquals(static::TEST_PAYLOAD['fields']['email'], $data['fields']['email']);
        $this->assertEquals(static::TEST_PAYLOAD['fields']['companyName'], $data['fields']['companyName']);
    }
}
