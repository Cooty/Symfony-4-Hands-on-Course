<?php

namespace App\Security;

use Psr\Log\LoggerInterface;

class TokenGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param int $length
     * @return string
     */
    public function getRandomSecureToken(int $length): string
    {
        $maxNumber = strlen(self::ALPHABET);
        $token = '';

        try {
            for ($i = 0; $i < $length; $i++) {
                $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
            }
        } catch(\Exception $e) {
            $this->logger->error($e->getMessage(). ' '.$e->getTraceAsString());
        }

        return $token;
    }
}