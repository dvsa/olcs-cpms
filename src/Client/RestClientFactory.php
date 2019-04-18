<?php
namespace Dvsa\Olcs\Cpms\Client;

use GuzzleHttp\Client;

/**
 * Class RestClientFactory
 *
 * @package CpmsClient\Client
 */
class HttpClientFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $domain = $serviceLocator->get('cpms\service\domain');
        $config = $serviceLocator->get('config');
        $restOptions = $config['cpms_api']['rest_client']['options'];
        $restOptions['domain'] = $domain;

        $options = new ClientOptions($restOptions);
        $client = new Client($options->getDomain());
        $httpClient = new HttpClient($client, $options);


        return $httpClient;
    }
}
