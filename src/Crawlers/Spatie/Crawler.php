<?php
namespace Dmoen\Crawler\Crawlers\Spatie;

use Dmoen\Crawler\Crawlers\CrawlerInterface;
use Spatie\Crawler\Crawler as CrawlSource;
use GuzzleHttp\RequestOptions;
use Spatie\Crawler\CrawlInternalUrls;

class Crawler implements CrawlerInterface
{
	
	private $url;

	private $crawlTool;

	private $observer;

	public function setUrl(string $url)
	{
		$this->url = $url;
		$this->crawlTool = CrawlSource::create([
            RequestOptions::COOKIES => true,
            RequestOptions::CONNECT_TIMEOUT => 25,
            RequestOptions::TIMEOUT => 25,
            RequestOptions::ALLOW_REDIRECTS => false,
            RequestOptions::VERIFY => false
        ]);
		$this->observer = new Observer();

		return $this;
	}

	public function ignoreExternal()
    {
        $this->crawlTool->setCrawlProfile(new CrawlInternalUrls($this->url));

        return $this;
    }

    public function watchStatus($status, Callable $onFound)
    {
        $this->observer->setUrlWatchCallback($status, $onFound);

        return $this;
    }

	public function setOnFail(Callable $onFail)
	{
		$this->observer->setUrlCrawlFailCallback($onFail);

		return $this;
	}

	public function setOnSuccess(Callable $onSuccess)
	{
		$this->observer->setUrlCrawlSuccessCallback($onSuccess);

		return $this;
	}

	public function setOnFinsh(Callable $onFinish)
	{
		$this->observer->setCrawlCompletedCallback($onFinish);

		return $this;
	}

	public function setOnCrawlStart(Callable $onStart)
	{
		$this->observer->setUrlCrawlStartCallback($onStart);

		return $this;
	}

	public function start()
	{
		$this->crawlTool
    		->setCrawlObserver($this->observer)
    		->startCrawling($this->url);
	}	
}