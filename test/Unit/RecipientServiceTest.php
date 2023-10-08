<?php

namespace Juanparati\Inmobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\Inmobile\Models\Recipient;

class RecipientServiceTest extends InmobileTestBase
{
    /**
     * Test recipient creation.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testCreateRecipient()
    {

        $mockedResponse = json_decode(static::loadMockedResponse('recipientResponse.json'), true);

        Http::fake([
            "lists/{$mockedResponse['listId']}/recipients" => Http::response($mockedResponse),
        ]);

        $resp = $this->api()
            ->recipients()
            ->create($mockedResponse['listId'], new Recipient($mockedResponse));

        $this->assertInstanceOf(Recipient::class, $resp);
        $this->assertEquals($mockedResponse, $resp->toArray());
    }

    /**
     * Test find recipient by Id.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testFindById()
    {
        $mockedResponse = json_decode(static::loadMockedResponse('recipientResponse.json'), true);

        Http::fake([
            "lists/{$mockedResponse['listId']}/recipients/{$mockedResponse['id']}" => Http::response($mockedResponse),
        ]);

        $resp = $this->api()
            ->recipients()
            ->findById($mockedResponse['listId'], $mockedResponse['id']);

        $this->assertInstanceOf(Recipient::class, $resp);
        $this->assertEquals($mockedResponse, $resp->toArray());
    }

    /**
     * Test find recipient by number.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testFindByNumber()
    {
        $mockedResponse = json_decode(static::loadMockedResponse('recipientResponse.json'), true);

        Http::fake([
            "lists/{$mockedResponse['listId']}/recipients/ByNumber?countryCode={$mockedResponse['numberInfo']['countryCode']}&phoneNumber={$mockedResponse['numberInfo']['phoneNumber']}" => Http::response($mockedResponse),
        ]);

        $resp = $this->api()
            ->recipients()
            ->findByNumber(
                $mockedResponse['listId'],
                $mockedResponse['numberInfo']['countryCode'],
                $mockedResponse['numberInfo']['phoneNumber']
            );

        $this->assertInstanceOf(Recipient::class, $resp);
        $this->assertEquals($mockedResponse, $resp->toArray());
    }

}
