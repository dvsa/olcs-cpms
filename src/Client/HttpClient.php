<?php
declare(strict_types=1);
namespace Dvsa\Olcs\Cpms\Client;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClient
 *
 * @package Dvsa\Olcs\Cpms\Client
 */
class HttpClient
{

    const CONTENT_TYPE_FORMAT = 'application/vnd.dvsa-gov-uk.v%d%s; charset=UTF-8';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientOptions
     */
    protected $clientOptions;


    public function __construct(Client $client, ClientOptions $clientOptions)
    {
        $this->client = $client;
        $this->clientOptions = $clientOptions;
    }

    public function get(string $endpoint, array $data)
    {
        $response = $this->client->get($endpoint, $this->buildOptions('get', $data));

        return $this->decodeResponse($response);
    }

    public function post(string $endpoint, array $data)
    {
        $response = $this->client->post($endpoint, $this->buildOptions('post', $data));

        return $this->decodeResponse($response);
    }

    protected function decodeResponse(ResponseInterface $response)
    {
        $decoded = json_decode($response->getBody()->getContents(), true);

        if (empty($decoded) && json_last_error() !== JSON_ERROR_NONE) {
            $response->getBody()->rewind();
            return $response->getBody()->getContents();
        }

        return $decoded;
    }

    protected function buildOptions(string $method, array $data): array
    {
        switch ($method) {
            case 'post':
                $options = $this->buildPostQuery($data);
                break;
            case 'get':
            default:
                $options = $this->buildGetQuery($data);
        }

        $options['headers'] = array_merge($options['headers'], $this->buildHeaders());
        return $options;
    }
    
    protected function buildGetQuery(array $data): array
    {
        return [
            'headers' => [
                'Content-Type' => $this->getContentType('get')
            ],
            'query' => $data
        ];
    }

    protected function buildPostQuery(array $data): array
    {
        return [
            'headers' => [
                'Content-Type' => $this->getContentType('post')
            ],
            'json' => json_encode($data)
        ];
    }

    protected function getContentType(string $method): string
    {
        $version = $this->clientOptions->getVersion();
        $format = ($method === 'post') ? "+json" : "";
        return sprintf(static::CONTENT_TYPE_FORMAT, $version, $format);
    }

    protected function buildHeaders(): array
    {
        return $this->clientOptions->getHeaders();
    }
}
