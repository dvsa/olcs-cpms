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
    public function get_returns_decoded_json_data_with_array()
    {
        $client = $this->setUpMockClient();
        $clientOptions = new ClientOptions([]);
        $sut  = new HttpClient($client, $clientOptions);
        $data = ["data"=>"test"];
        $this->appendToHandler(200,[],json_encode($data));
        $response = $sut->get("/fake-endpoint",[]);
        $this->assertSame($data, $response);
    }

    /**
     * @test
     */
    public function get_returns_decoded_json_data_with_class_as_array()
    {
        $client = $this->setUpMockClient();
        $clientOptions = new ClientOptions([]);
        $sut  = new HttpClient($client, $clientOptions);
        $data = (new class(){
            public $prop = "test";
        });
        $this->appendToHandler(200,[],json_encode($data));
        $response = $sut->get("/fake-endpoint",[]);
        $this->assertSame(['prop' => 'test'], $response);
    }

    /**
     * @test
     */
    public function post_returns_decoded_json()
    {
        $client = $this->setUpMockClient();
        $clientOptions = new ClientOptions([]);
        $sut  = new HttpClient($client, $clientOptions);
        $data = ['data' => 'test'];
        $this->appendToHandler(200,[],json_encode($data));
        $response = $sut->post("/fake-endpoint",$data);
        $this->assertSame($data, $response);
    }
}
