<?php

namespace Tests\Modules\Providers\EntityProvider;

use GuzzleHttp\Psr7\Request;
use Local\Modules\Providers\EntityProvider\EntityProvider;
use Monolog\Test\TestCase;
use Tests\Helpers\Client\ClientHelper;
use Tests\Helpers\Logger\LoggerHelper;

class EntityProviderTest extends TestCase
{
    public function testGetOne()
    {
        $entityProvider = new EntityProvider(LoggerHelper::getLogger(), ClientHelper::getClient());
        $request = new Request('GET', 'https://jsonplaceholder.typicode.com/posts/1');
        $response = $entityProvider->getOne($request);
        $this->assertEquals(1, $response->id);
    }

    public function testGetMany()
    {
        $entityProvider = new EntityProvider(LoggerHelper::getLogger(), ClientHelper::getClient());
        $request = new Request('GET', 'https://jsonplaceholder.typicode.com/posts');
        $response = $entityProvider->getMany($request);
        $this->assertEquals(100, count($response));
    }
}