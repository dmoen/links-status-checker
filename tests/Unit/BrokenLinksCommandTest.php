<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Dmoen\Crawler\Command\BrokenLinksCommand;

class BrokenLinksCommandTest extends TestCase
{
    private $commandTester;

    protected function setUp()
    {
        parent::setUp();

        $application = new Application();
        $application->add(new BrokenLinksCommand());
        $command = $application->find('broken-links');
        $this->commandTester = new CommandTester($command);
    }

    public function test_it_finds_broken_links()
    {
        $this->commandTester->execute(["url" => "http://localhost:8080/link3"]);

        $this->assertRegExp('/Found broken link: http:\/\/localhost:8080\/notExists/', $this->commandTester->getDisplay());
        $this->assertRegExp('/Found broken link: http:\/\/localhost:8080\/notExists2/', $this->commandTester->getDisplay());
    }

    public function test_it_displays_the_correct_number_of_crawled_links()
    {
        $this->commandTester->execute(["url" => "http://localhost:8080/link3"]);

        $this->assertRegExp('/Found: 2 broken urls out of totally 3./', $this->commandTester->getDisplay());
    }
}