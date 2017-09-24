<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Dmoen\Crawler\Command\StatusSearchCommand;

class StatusSearchCommandTest extends TestCase
{
    private $commandTester;

    protected function setUp()
    {
        parent::setUp();

        $application = new Application();
        $application->add(new StatusSearchCommand());
        $command = $application->find('status-search');
        $this->commandTester = new CommandTester($command);
    }

    public function test_it_finds_a_response_code()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link2",
            "code" => 200
        ]);

        $this->assertRegExp('/Found the code: 200/', $this->commandTester->getDisplay());
    }

    public function test_it_displays_the_correct_number_of_found_codes()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link2",
            "code" => 200
        ]);

        $this->assertRegExp('/Found: 2 codes. Crawled 4 urls./', $this->commandTester->getDisplay());
    }

    public function test_it_ignores_external_urls()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link1",
            "code" => 200
        ]);

        $this->assertRegExp('/Crawled 1 urls./', $this->commandTester->getDisplay());
    }
}