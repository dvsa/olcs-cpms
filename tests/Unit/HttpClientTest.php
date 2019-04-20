<?php

namespace Dvsa\Olcs\Test\Unit;

use Dvsa\Olcs\Cpms\Client\ClientOptions;
use Dvsa\Olcs\Cpms\Client\HttpClient;

use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    use GuzzleTestTrait;

    /**
     * @test
     */
    public function get_returns_decoded_json_data()
    {
        $client = $this->setUpMockClient();
        $clientOptions = new ClientOptions([]);
        $sut  = new HttpClient($client, $clientOptions);
        $data = ["data"=>"test"];
        $this->appendToHandler(200,[],json_encode($data));
        $response = $sut->get("/fake-enpoint",[]);
        $this->assertSame($data, $response);
    }

    public function testPost()
    {

    }




}
