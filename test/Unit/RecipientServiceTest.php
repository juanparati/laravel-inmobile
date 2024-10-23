<?php

namespace Juanparati\InMobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\InMobile\Models\Recipient;

class RecipientServiceTest extends InMobileTestBase
{
    /**
     * Test recipient creation.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
     * @throws \Juanparati\InMobile\Exceptions\InMobileAuthorizationException
     * @throws \Juanparati\InMobile\Exceptions\InMobileRequestException
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
