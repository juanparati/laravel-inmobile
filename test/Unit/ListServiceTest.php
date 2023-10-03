<?php

namespace Juanparati\Inmobile\Test\Unit;

use Juanparati\Inmobile\Models\PaginatedResults;
use Juanparati\Inmobile\Models\Recipient;

class ListServiceTest extends InmobileTestBase
{
    /**
     * Test that is possible to retrieve all the lists.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testAllLists()
    {
        $resp = $this->api()
            ->lists()
            ->all(2);

        $this->assertInstanceOf(PaginatedResults::class, $resp);
    }

    /**
     * Test the entire the list lifecycle.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testListLifecycle()
    {

        $respCreate = $this->api()
            ->lists()
            ->create('(UNIT TEST)');

        $this->assertNotNull($respCreate->getId());

        $respList = $this->api()
            ->lists()
            ->find($respCreate->getId());

        $this->assertEquals($respCreate->getName(), $respList->getName());

        $respDelete = $this->api()
            ->lists()
            ->delete($respCreate->getId());

        $this->assertEmpty($respDelete);
    }

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
        $respList = $this->api()
            ->lists()
            ->create('(UNIT TEST REC)');

        $this->assertNotNull($respList->getId());

        $respRecipient = $this->api()
            ->recipients()
            ->create(
                $respList->getId(),
                Recipient::make('45', '12345678')
                    ->addField('firstname', 'John')
                    ->addField('lastname', 'Random')
                    ->addField('custom1', 'custom')
                    ->setCreatedAt(now()->subDay())
            );

        $this->assertNotNull($respRecipient->getId());

        $respRecipient = $this->api()
            ->recipients()
            ->updateOrCreateByNumber(
                $respList->getId(),
                $respRecipient->getCode(),
                $respRecipient->getPhone(),
                Recipient::make('45', '12345679')
                    ->addField('firstname', 'John2')
                    ->addField('lastname', 'Random')
                    ->addField('custom1', 'custom')
            );

        $this->assertNotNull($respRecipient->getId());
        $this->assertEquals('12345679', $respRecipient->getPhone());
        $this->assertEquals('John2', $respRecipient->getField('firstname'));

        $this->api()
            ->lists()
            ->delete($respList->getId());
    }
}
