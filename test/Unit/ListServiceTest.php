<?php

namespace Juanparati\Inmobile\Test\Unit;

use Illuminate\Support\Facades\Http;
use Juanparati\Inmobile\Models\PaginatedResults;
use Juanparati\Inmobile\Models\RecipientList;

class ListServiceTest extends InmobileTestBase
{
    /**
     * Test that is possible to retrieve all the lists.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testAllLists()
    {
        $mockedLists          = json_decode(static::loadMockedResponse('listsResponses.json'), true);
        $mockedListsPaginated = array_chunk($mockedLists, 2);

        Http::fakeSequence()
            ->push(
                static::loadMockedResponse('allListsResponse.json', [
                    '{{LISTS}}'   => json_encode($mockedListsPaginated[0]),
                    '{{IS_LAST}}' => 'false',
                ]
                ))
            ->push(
                static::loadMockedResponse('allListsResponse.json', [
                    '{{LISTS}}'   => json_encode($mockedListsPaginated[1]),
                    '{{IS_LAST}}' => 'true',
                ]
                ));

        $resp = $this->api()
            ->lists()
            ->all(2);

        $this->assertInstanceOf(PaginatedResults::class, $resp);

        foreach ($resp as $k => $r) {
            $this->assertEquals($mockedLists[$k], $r->toArray());
        }
    }

    /**
     * Test retrieve a list.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testList()
    {
        $mockedList = json_decode(static::loadMockedResponse('listsResponses.json'), true)[0];

        Http::fake([
            "lists/{$mockedList['id']}" => Http::response($mockedList),
        ]);

        $resp = $this->api()
            ->lists()
            ->find($mockedList['id']);

        $this->assertInstanceOf(RecipientList::class, $resp);
        $this->assertEquals($mockedList, $resp->toArray());
    }

    /**
     * Test list creation.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testCreateList()
    {
        $mockedList = json_decode(static::loadMockedResponse('listsResponses.json'), true)[0];

        Http::fake([
            'lists' => Http::response($mockedList),
        ]);

        $resp = $this->api()
            ->lists()
            ->create($mockedList['name']);

        $this->assertInstanceOf(RecipientList::class, $resp);
        $this->assertEquals($mockedList, $resp->toArray());
    }

    /**
     * Test
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testDeleteList()
    {
        $mockedList = json_decode(static::loadMockedResponse('listsResponses.json'), true)[0];

        Http::fake([
            "lists/{$mockedList['id']}" => Http::response('', 204),
        ]);

        $resp = $this->api()
            ->lists()
            ->delete($mockedList['id']);

        $this->assertEmpty($resp);
    }

    /**
     * Test update list.
     *
     * @return void
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileAuthorizationException
     * @throws \Juanparati\Inmobile\Exceptions\InmobileRequestException
     */
    public function testUpdateList()
    {
        $mockedList             = json_decode(static::loadMockedResponse('listsResponses.json'), true);
        $mockedResponse         = $mockedList[0];
        $mockedResponse['name'] = $mockedList[1]['name'];

        Http::fake([
            "lists/{$mockedResponse['id']}" => Http::response($mockedResponse),
        ]);

        $resp = $this->api()
            ->lists()
            ->update($mockedResponse['id'], $mockedResponse['name']);

        $this->assertInstanceOf(RecipientList::class, $resp);
        $this->assertEquals($mockedResponse['name'], $resp->getName());
    }
}
