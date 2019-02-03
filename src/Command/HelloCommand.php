<?php

namespace App\Command;

use App\Service\Greetings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    /**
     * @var Greetings
     */
    private $greetings;

    public function __construct(Greetings $greetings)
    {
        $this->greetings = $greetings;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:say-hello')
            ->setDescription('Says hello to the user')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $greetings = $this->greetings->greet($name);
        $output->writeln([
            'Hello from the app',
            '====================',
            $greetings,
            '===================='
        ]);
    }


}