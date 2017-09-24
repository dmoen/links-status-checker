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
    		->start();

    	$this->assertTrue($called);

    }

    public function test_it_spots_a_dead_link_deep_down()
    {
        $crawler = new Crawler();

        $args = null;

        $crawler->setUrl("http://localhost:8080/link3")
            ->setOnFail(function($url, $statusCode, $foundOnUrl) use (&$args){
                $args = func_get_args();
            })
            ->start();

        $this->assertEquals('http://localhost:8080/notExists2', $args[0]);
        $this->assertEquals(404, $args[1]);
        $this->assertEquals('http://localhost:8080/link3', $args[2]);
    }

    public function test_it_watches_a_status()
    {
        $crawler = new Crawler();

        $called = false;

        $crawler->setUrl("http://localhost:8080/link7")
            ->watchStatus(500, function() use (&$called){
                $called = true;
            })
            ->start();

        $this->assertTrue($called);
    }

    public function test_invalid_urls_ar_threated_as_404()
    {
        $crawler = new Crawler();

        $called = false;

        $crawler->setUrl("lorem ipsum")
            ->watchStatus(404, function() use (&$called){
                $called = true;
            })
            ->start();

        $this->assertTrue($called);
    }

    public function test_it_ignores_unsafe_ssl_certificates()
    {
        $crawler = new Crawler();

        $called = false;

        $crawler->setUrl("https://untrusted-root.badssl.com")
            ->watchStatus(200, function() use (&$called){
                $called = true;
            })
            ->start();

        $this->assertTrue($called);
    }
}