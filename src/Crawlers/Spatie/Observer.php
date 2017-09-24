<?php
namespace Dmoen\Crawler\Crawlers\Spatie;

use Dmoen\Crawler\CrawlerInterface;
use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

class Observer implements CrawlObserver
{

    private $onUrlCrawlStart;

	private $onUrlCrawlSuccess;

	private $onUrlCrawlFailed;

	private $onComplete;

	private $watchStatus;

    private $watchCallback;

    public function setUrlWatchCallback($watchStatus, Callable $watchCallback)
    {
        $this->watchStatus = $watchStatus;
        $this->watchCallback = $watchCallback;

        return $this;
    }

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
        if(!$this->onUrlCrawlStart){
            return;
        }

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
        $code = ($response) ? $response->getStatusCode() : 404;

        if($code == $this->watchStatus && $this->watchCallback){
            call_user_func($this->watchCallback, (string)$url, $code, (string)$foundOnUrl, $response);
        }

    	if($this->onUrlCrawlSuccess && $code >= 200 && $code < 400){
    		call_user_func($this->onUrlCrawlSuccess, (string)$url, $code, (string)$foundOnUrl, $response);
    		return;
    	}

    	if($this->onUrlCrawlFailed && $code >= 400){
            call_user_func($this->onUrlCrawlFailed, (string)$url, $code, (string)$foundOnUrl, $response);
        }
    }

    /**
     * Called when the crawl has ended.
     *
     * @return void
     */
    public function finishedCrawling()
    {
        if(!$this->onComplete){
            return;
        }

        call_user_func($this->onComplete);
    }
}