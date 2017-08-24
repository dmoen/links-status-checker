<?php
namespace Dmoen\Crawler\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    
    protected function configure()
    {
	    $this
	        // the name of the command (the part after "bin/console")
	        ->setName('crawler:find-broken')

	        // the short description shown while running "php bin/console list"
	        ->setDescription('Find broken links on your website.')

	        // the full command description shown when running the command with
	        // the "--help" option
	        ->setHelp('Find broken links on your website.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('testing');
    }

}