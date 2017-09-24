<?php
namespace Dmoen\Crawler\Command;

use Dmoen\Crawler\Crawlers\CrawlerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusSearchCommand extends Command
{
    private $crawled = 0;

    private $found = 0;

    protected function configure()
    {
        $this
            ->setName('status-search')
            ->setDescription('Response code search.')
            ->addArgument('url', InputArgument::REQUIRED, 'The url to crawl.')
            ->addArgument('code', InputArgument::REQUIRED, 'The code to search for.')
            ->setHelp('Search a website for a specific http response code.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Crawling...");

        $crawler = CrawlerFactory::build();

        $code = $input->getArgument('code');

        $crawler->setUrl($input->getArgument('url'))
            ->ignoreExternal()
            ->setOnCrawlStart(function(){
                $this->crawled++;
            })
            ->watchStatus($code, function($url, $code, $foundOnUrl) use ($output){
                $this->found++;

                $output->writeln([
                    "\n<fg=green>Found the code:</> $code",
                    "For url: $url"
                ]);

                if($foundOnUrl){
                    $output->writeln("Found while crawling: $foundOnUrl");
                }
            })
            ->setOnFinsh(function() use ($output){
                $output->writeln("\nFound: $this->found codes. Crawled $this->crawled urls.");
            })
            ->start();
    }
}