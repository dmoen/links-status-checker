<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Dmoen\Crawler\Command\CrawlCommand;

class CrawlCommandTest extends TestCase
{
    public function testExample()
    {
        // mock the Kernel or create one depending on your needs
        $application = new Application();
        $application->add(new CrawlCommand());

        $command = $application->find('crawler:find-broken');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertRegExp('/test/', $commandTester->getDisplay());

    }
}