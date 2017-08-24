<?php

use PHPUnit\Framework\TestCase;
use Dmoen\Crawler\Crawlers\Spatie\Crawler;

class CrawlerTest extends TestCase
{
    public function test_spots_a_dead_link()
    {

    	$crawler = new Crawler();

    	$called = false;

    	$crawler->setUrl("http://localhost:8080/notExists")
    		->setOnFail(function() use (&$called){
    			$called = true;
    		})
    		->setOnCrawlStart(function(){

    		})
    		->setOnFinsh(function(){

    		})    		
    		->start();

    	$this->assertTrue($called);

    }
}