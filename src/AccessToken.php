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

    /**
     * @var bool
     */
    private $__strictMode__;

    /**
     * AccessToken constructor.
     *
     * @param array|null $options
     */
    public function __construct(?array $options = null)
    {
        $this->__strictMode__ = true;
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $this->__set($key, $value);
            }
        }
    }

    /**
     * @param int $issuedAt
     */
    public function setIssuedAt(int $issuedAt): void
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
    public function setExpiresIn(string $expiresIn): void
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return (int) $this->expiresIn;
    }

    /**
     * @param string $scope
     */
    public function setScope(string $scope): void
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
    public function setTokenType(string $tokenType)
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
    public function setSalesReference(string $salesReference)
    {
        $this->salesReference = $salesReference;
    }

    public function __set($key, $value): void
    {
        $setter = 'set' . str_replace('_', '', $key);

        if (is_callable(array($this, $setter))) {
            $this->{$setter}($value);

            return;
        }

        if ($this->__strictMode__) {
            throw new Exception\BadMethodCallException(sprintf(
                'The option "%s" does not have a callable "%s" ("%s") setter method which must be defined',
                $key,
                'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))),
                $setter
            ));
        }
    }
}
