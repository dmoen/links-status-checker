<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Dmoen\Crawler\Command\RespSearchCommand;

class RespSearchCommandTest extends TestCase
{
    private $commandTester;

    protected function setUp()
    {
        parent::setUp();

        $application = new Application();
        $application->add(new RespSearchCommand());
        $command = $application->find('response-search');
        $this->commandTester = new CommandTester($command);
    }

    public function test_it_finds_a_text_string()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link3",
            "string" => "You are on link3"
        ]);

        $this->assertRegExp('/Found the text: You are on link3/', $this->commandTester->getDisplay());
    }

    public function test_it_displays_the_correct_number_of_found_texts()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link2",
            "string" => "You are"
        ]);

        $this->assertRegExp('/Found: 2 strings. Crawled 4 urls./', $this->commandTester->getDisplay());
    }

    public function test_it_ignores_external_urls()
    {
        $this->commandTester->execute([
            "url" => "http://localhost:8080/link1",
            "string" => "Lorem ipsum"
        ]);

        $this->assertRegExp('/Crawled 1 urls./', $this->commandTester->getDisplay());
    }
}