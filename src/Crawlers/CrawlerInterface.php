<?php
namespace Dmoen\Crawler\Crawlers;

interface CrawlerInterface
{
	public function setUrl(string $url);

	public function setOnFail(Callable $onFail);

	public function setOnSuccess(Callable $onSuccess);

	public function setOnFinsh(Callable $onFinish);

	public function setOnCrawlStart(Callable $onStart);

	public function watchStatus($status, Callable $onFound);

	public function ignoreExternal();

	public function start();
}