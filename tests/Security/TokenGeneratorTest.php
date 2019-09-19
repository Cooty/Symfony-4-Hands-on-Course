<?php

namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class TokenGeneratorTest extends TestCase
{
    private function loggerMock()
    {
        return $this->createMock(LoggerInterface::class);
    }

    public function testTokenGeneration()
    {
        $length = 30;
        $logger = $this->loggerMock();
        $tokenGenerator = new TokenGenerator($logger);
        $token = $tokenGenerator->getRandomSecureToken($length);

        $this->assertEquals($length, strlen($token));
        $this->assertTrue(ctype_alnum($token), 'Token contains incorrect characters');
    }
}