<?php
namespace Dmoen\Crawler\Command;

use Dmoen\Crawler\Crawlers\CrawlerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BrokenLinksCommand extends Command
{

    private $crawled = 0;

    private $failed = 0;

    protected function configure()
    {
	    $this
	        ->setName('broken-links')
	        ->setDescription('Find broken links.')
            ->addArgument('url', InputArgument::REQUIRED, 'The url to crawl.')
	        ->setHelp('Find broken links on a website.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Crawling...");

        $crawler = CrawlerFactory::build();

        $crawler->setUrl($input->getArgument('url'))
            ->setOnCrawlStart(function(){
                $this->crawled++;
            })
            ->setOnFail(function($url, $code, $foundOnUrl) use ($output){
                $this->failed++;

                $output->writeln([
                    "\n<fg=red>Found broken link:</> $url",
                    "With status code: $code"
                ]);

                if($foundOnUrl){
                    $output->writeln("Found while crawling: $foundOnUrl");
                }
            })
            ->setOnFinsh(function() use ($output){
                $output->writeln("\nFound: $this->failed broken urls out of totally $this->crawled.");
            })
            ->start();
    }

}