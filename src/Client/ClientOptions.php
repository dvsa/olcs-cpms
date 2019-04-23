<?php
declare(strict_types=1);
namespace Dvsa\Olcs\Cpms\Client;

/**
 * Class ClientOptions
 * @codeCoverageIgnore
 */
class ClientOptions
{
    /** @var int */
    protected $version = 1;
    /** @var  string */
    protected $clientId;
    /** @var  string */
    protected $clientSecret;
    /** @var  string */
    protected $userId;
    /** @var array */
    protected $endPoints = array();
    /** @var  string */
    protected $customerReference;
    /** @var  string */
    protected $grantType;
    /** @var int */
    protected $timeout = 30;
    /** @var string */
    protected $domain;
    /** @var array */
    protected $headers = array();

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param string $customerReference
     */
    public function setCustomerReference(string $customerReference)
    {
        $this->customerReference = $customerReference;
    }

    /**
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->customerReference;
    }

    /**
     * @param array $endPoints
     */
    public function setEndPoints(array $endPoints)
    {
        $this->endPoints = $endPoints;
    }

    /**
     * @return array
     */
    public function getEndPoints(): array
    {
        return $this->endPoints;
    }

    /**
     * @param string $grantType
     */
    public function setGrantType(string $grantType)
    {
        $this->grantType = $grantType;
    }

    /**
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
