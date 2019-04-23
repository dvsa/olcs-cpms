<?php

namespace Dvsa\Olcs\Cpms\Test\Unit\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Trait GuzzleTestTrait
 *
 * @package Dvsa\Olcs\Cpms\Test\Unit\Client
 */
trait GuzzleTestTrait
{
    /**
     * @var MockHandler
     */
    public $mockHandler;

    /**
     * @return Client
     */
    public function setUpMockClient(): Client
    {
        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handler]);
        return $client;
    }

    public function appendToHandler($statusCode = 200, $headers = [], $body = '', $version = '1.1', $reason = null)
    {
        if (!$this->mockHandler instanceof MockHandler) {
            $this->setUpMockClient();
        }
        $response = new Response($statusCode, $headers, $body, $version, $reason);

        $this->mockHandler->append($response);
    }
}
