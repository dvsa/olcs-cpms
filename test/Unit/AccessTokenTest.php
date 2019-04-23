<?php

namespace Dvsa\Olcs\Cpms\Test\Unit;

use Dvsa\Olcs\Cpms\AccessToken;
use PHPUnit\Framework\TestCase;

class AccessTokenTest extends TestCase
{
    /**
     * @test
     * @dataProvider isExpiredDataProvider
     * @param int $issuedAt
     * @param int $expiresIn
     * @param bool $isExpired
     */
    public function isExpired(int $issuedAt, int $expiresIn, bool $isExpired)
    {
        $accessToken = new AccessToken([
            'issuedAt' => $issuedAt,
            'expiresIn' => $expiresIn
        ]);

        $this->assertEquals($isExpired, $accessToken->isExpired());
    }

    /**
     * @test
     */
    public function getAuthorisationHeader()
    {
        $accessToken = new AccessToken([
            'accessToken' => 'testing'
        ]);

        $this->assertStringStartsWith('Bearer ', $accessToken->getAuthorisationHeader());
    }

    /**
     * @test
     */
    public function strictMode()
    {
        $this->expectException(\BadFunctionCallException::class);
        new AccessToken(['incorrect' => true]);
    }

    public function isExpiredDataProvider()
    {
        return [
            'has expired' => [
                'issuedAt' => time() - 300,
                'expiresIn' => 240,
                'isExpired' => true
            ],
            "hasn't expired" => [
                'issuedAt' => time(),
                'expiresIn' => 60,
                'isExpired' => false
            ]
        ];
    }
}
