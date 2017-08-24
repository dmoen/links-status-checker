<?php
namespace Dmoen\Crawler\Crawlers\Spatie;

use Dmoen\Crawler\CrawlerInterface;
use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

class Observer implements CrawlObserver
{

    private $onUrlCrawlStart;

	private $onUrlCrawled;

	private $onUrlCrawlSuccess;

	private $onUrlCrawlFailed;

	private $onComplete;


	public function setUrlCrawlStartCallback(Callable $onUrlCrawlStart)
	{
		$this->onUrlCrawlStart = $onUrlCrawlStart;

		return $this;
	}	


	public function setUrlCrawlSuccessCallback(Callable $onUrlCrawlSuccess)
	{
		$this->onUrlCrawlSuccess = $onUrlCrawlSuccess;

		return $this;
	}

	public function setUrlCrawlFailCallback(Callable $onUrlCrawlFailed)
	{
		$this->onUrlCrawlFailed = $onUrlCrawlFailed;

		return $this;
	}	

	public function setCrawlCompletedCallback(Callable $onComplete)
	{
		$this->onComplete = $onComplete;

		return $this;
	}	

    /**
     * Called when the crawler will crawl the url.
     *
     * @param \Spatie\Crawler\Url $url
     *
     * @return void
     */
    public function willCrawl(Url $url)
    {
        call_user_func($this->onUrlCrawlStart, (string)$url);
    }

    /**
     * Called when the crawler has crawled the given url.
     *
     * @param \Spatie\Crawler\Url $url
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Spatie\Crawler\Url $foundOnUrl
     *
     * @return void
     */
    public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null)
    {
    	if($response->getStatusCode() >= 200 && $response->getStatusCode() < 400){
    		call_user_func($this->onUrlCrawlSuccess, (string)$url, $response->getStatusCode(), (string)$foundOnUrl);
    		return;
    	}

    	call_user_func($this->onUrlCrawlFailed, (string)$url, $response->getStatusCode(), (string)$foundOnUrl);
    }

    /**
     * Called when the crawl has ended.
     *
     * @return void
     */
    public function finishedCrawling()
    {
        call_user_func($this->onComplete);
    }
}