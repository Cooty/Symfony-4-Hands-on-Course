<?php
/**
 * Created by PhpStorm.
 * User: tamas
 * Date: 17/01/19
 * Time: 16:06
 */

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greetings
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $message;

    public function __construct(LoggerInterface $logger, string $message)
    {
        $this->logger = $logger;
        $this->message = $message;
    }

    /**
     * @param string $name
     * @return string
     */
    public function greet(string $name = ''): string
    {
        $this->logger->info("Greeted $name");

        return "{$this->message} $name";
    }
}