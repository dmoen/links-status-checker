<?php
namespace Dmoen\Crawler\Crawlers;

use Dmoen\Crawler\Crawlers\Spatie\Crawler;

class CrawlerFactory
{

	public static function build()
	{
		return new Crawler;
	}

}