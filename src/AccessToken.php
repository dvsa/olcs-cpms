<?php
declare(strict_types=1);

namespace Dvsa\Olcs\Cpms;

/**
 * Class AccessToken
 *
 * @package CpmsClient\Data
 */
class AccessToken
{
    const INVALID_ACCESS_TOKEN = 114;
    /**
     * @var string
     */
    protected $expiresIn;
    /** @var  string */
    protected $tokenType;
    /** @var  string */
    protected $accessToken;
    /** @var  string */
    protected $scope;
    /** @var  int */
    protected $issuedAt;
    /** @var  string */
    protected $salesReference;

    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    /**
     * @param int $issuedAt
     */
    public function setIssuedAt($issuedAt): void
    {
        $this->issuedAt = $issuedAt;
    }

    /**
     * @return int
     */
    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $expiresIn
     */
    public function setExpiresIn($expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return (int)$this->expiresIn;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope): void
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType): string
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    /**
     * Is token expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        $expiryTime = (int)$this->getIssuedAt() + $this->getExpiresIn();

        return ($expiryTime < time());
    }

    /**
     * Get Auth Header
     *
     * @return string
     */
    public function getAuthorisationHeader(): string
    {
        return 'Bearer ' . $this->getAccessToken();
    }

    /**
     * @param string $salesReference
     */
    public function setSalesReference($salesReference): string
    {
        $this->salesReference = $salesReference;
    }
}
