<?php

namespace Application;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Cookie\SetCookie as CookieParser;
use IvoPetkov\HTML5DOMDocument;

class Application
{
    public $base_url = 'https://www.mzv.cz/';
    private $client;
    private $dom;

    public function __construct()
    {
        $this->client = new Client(['cookies' => true]);
        $this->dom = new HTML5DOMDocument();
    }

    public function getCode(){

    }

}