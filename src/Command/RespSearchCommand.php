<?php
namespace Dmoen\Crawler\Command;

use Dmoen\Crawler\Crawlers\CrawlerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RespSearchCommand extends Command
{
    private $crawled = 0;

    private $found = 0;

    protected function configure()
    {
        $this
            ->setName('response-search')
            ->setDescription('Search text string.')
            ->addArgument('url', InputArgument::REQUIRED, 'The url to crawl.')
            ->addArgument('string', InputArgument::REQUIRED, 'The string to search for.')
            ->setHelp('Search a website for a specific text string.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Crawling...");

        $crawler = CrawlerFactory::build();

        $text = $input->getArgument('string');

        $crawler->setUrl($input->getArgument('url'))
            ->ignoreExternal()
            ->setOnCrawlStart(function(){
                $this->crawled++;
            })
            ->setOnSuccess(function($url, $code, $foundOnUrl, $response) use ($output, $text){
                if($this->responseMatches($text, $response->getBody())){
                    $this->found++;

                    $output->writeln([
                        "\n<fg=green>Found the text:</> $text",
                        "On url: $url"
                    ]);
                }
            })
            ->setOnFinsh(function() use ($output){
                $output->writeln("\nFound: $this->found strings. Crawled $this->crawled urls.");
            })
            ->start();
    }

    private function responseMatches($text, $body)
    {
        return strpos($body, $text) !== false;
    }
}