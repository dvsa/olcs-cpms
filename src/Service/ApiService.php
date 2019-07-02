<?php
declare(strict_types=1);

namespace Dvsa\Olcs\Cpms\Service;

use Dvsa\Olcs\Cpms\AccessToken;
use Dvsa\Olcs\Cpms\Authenticate\CpmsIdentityProvider;
use Dvsa\Olcs\Cpms\Client\ClientOptions;
use Dvsa\Olcs\Cpms\Client\HttpClient;
use Psr\Log\LoggerInterface as Logger;

class ApiService
{
    const SCOPE_CARD = 'CARD';
    const SCOPE_CNP = 'CNP';
    const SCOPE_DIRECT_DEBIT = 'DIRECT_DEBIT';
    const SCOPE_CHEQUE = 'CHEQUE';
    const SCOPE_REFUND = 'REFUND';
    const SCOPE_QUERY_TXN = 'QUERY_TXN';
    const SCOPE_STORED_CARD = 'STORED_CARD';
    const SCOPE_CHARGE_BACK = 'CHARGE_BACK';
    const SCOPE_CASH = 'CASH';
    const SCOPE_POSTAL_ORDER = 'POSTAL_ORDER';
    const SCOPE_CHIP_PIN = 'CHIP_PIN';
    const SCOPE_ADJUSTMENT = 'ADJUSTMENT';
    const SCOPE_REPORT = 'REPORT';
    const CHEQUE_RD = 'CHEQUE_RD'; // refer to drawer
    const DIRECT_DEBIT_IC = 'DIRECT_DEBIT_IC'; // indemnity claim
    const REALLOCATE_PAYMENT = 'REALLOCATE'; // Reallocate payments by switch customer reference

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var ClientOptions
     */
    protected $options;

    /**
     * @var CpmsIdentityProvider
     */
    protected $identity;

    public function __construct(
        HttpClient $httpClient,
        CpmsIdentityProvider $identity,
        Logger $logger
    ) {
        $this->httpClient = $httpClient;
        $this->identity = $identity;
        $this->options = $httpClient->getClientOptions();
        $this->logger = $logger;
    }

    /**
     * @param string $endPoint
     * @param string $scope
     * @param array  $data
     *
     * @return array|mixed
     */
    public function get(string $endPoint, string $scope, array $data = [])
    {
        return $this->getAuthTokenAndProcessRequest($endPoint, $scope, HttpClient::METHOD_GET, $data);
    }


    /**
     * @param string $endPoint
     * @param string $scope
     * @param array  $data
     *
     * @return array|mixed
     */
    public function post(string $endPoint, string $scope, array $data)
    {
        return $this->getAuthTokenAndProcessRequest($endPoint, $scope, HttpClient::METHOD_POST, $data);
    }

    /**
     * @param string $endPoint
     * @param string $scope
     * @param array  $data
     *
     * @return array|mixed
     */
    public function put(string $endPoint, string $scope, array $data)
    {
        return $this->getAuthTokenAndProcessRequest($endPoint, $scope, HttpClient::METHOD_PUT, $data);
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * @return ClientOptions
     */
    public function getOptions(): ClientOptions
    {
        return $this->options;
    }

    /**
     * @return CpmsIdentityProvider
     */
    public function getIdentity(): CpmsIdentityProvider
    {
        return $this->identity;
    }

    /**
     * Process API request
     *
     * @param string     $endPoint
     * @param string     $scope  (CARD, DIRECT_DEBIT)
     * @param string     $method HTTP Method (GET, POST, PUT)
     * @param null|array $params
     *
     * @return array|mixed
     */
    protected function getAuthTokenAndProcessRequest(
        string $endPoint,
        string $scope,
        string $method,
        ?array $params = null
    ) {
        $salesReference = $this->getSalesReferenceFromParams($params);

        try {
            //Get access token
            $token = $this->getCpmsAccessToken($scope, $salesReference);

            if ($token instanceof AccessToken) {
                $headers = $this->getOptions()->getHeaders();
                $headers['Authorization'] = $token->getAuthorisationHeader();

                $this->getOptions()->setHeaders($headers);

                $response = $this->getHttpClient()->$method($endPoint, $params);

                return $response;
            } else {
                return $token;
            }
        } catch (\Exception $exception) {
            return $this->returnErrorMessage($exception);
        }
    }

    /**
     * @param string      $scope
     * @param string|null $salesReference
     *
     * @return AccessToken|mixed
     */
    protected function getCpmsAccessToken(string $scope, ?string $salesReference = null)
    {
        $payload = [
            'client_id' => $this->getIdentity()->getClientId(),
            'client_secret' => $this->getIdentity()->getClientSecret(),
            'user_id' => $this->getIdentity()->getUserId(),
            'grant_type' => $this->getOptions()->getGrantType(),
            'scope' => $scope,
        ];

        if (!empty($salesReference)) {
            $payload['sales_reference'] = $salesReference;
        }

        $client = $this->getHttpClient();

        $response = $client->post('/api/token', $payload);

        if (isset($response['access_token'])) {
            return new AccessToken(
                $response['access_token'],
                $response['expires_in'],
                time(),
                $response['scope'],
                $response['token_type'],
                $response['sales_reference']
            );
        }

        $this->logger->warning('Unable to get access token with data: ' . print_r($response, true));
        return $response;
    }

    private function returnErrorMessage(\Exception $exception): string
    {
        $errorId = $this->getErrorId();

        if (method_exists($exception, 'getResponse')) {
            $responseBody = $exception->getResponse()->getBody();
            $responseBody->rewind();
            $message = $responseBody->getContents();
        } else {
            $message = $exception->getMessage();
        }

        $logMessage = sprintf("An unknown error occurred, ID %s\n%s", $errorId, $message);
        $this->logger->error($logMessage);

        return $message;
    }

    /**
     * @param array|null $params
     *
     * @return string|null
     */
    private function getSalesReferenceFromParams(?array $params): ?string
    {
        if (!isset($params['payment_data'])) {
            return null;
        }

        $paymentRow = current($params['payment_data']);

        if (is_array($paymentRow) && isset($paymentRow['sales_reference'])) {
            return $paymentRow['sales_reference'];
        }
        return null;
    }

    /**
     * Return a unique identifier for the error message for tracking in the the logs
     */
    private function getErrorId(): string
    {
        return md5(uniqid('API'));
    }
}
