<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Cookie\SetCookie as CookieParser;

$client = new Client(['cookies' => true]);

$baseUrl = 'https://www.mzv.cz/';

// first
$response = $client->get("{$baseUrl}lvov/uk/x2004_02_03/x2016_05_18/x2017_11_24_1.html",
    [
        'headers' => [
            'User-Agent' => 'Tegos/1.1'
        ]
    ]
);

$dom = new IvoPetkov\HTML5DOMDocument();
$dom->loadHTML($response->getBody());

$scripts = $dom->getElementsByTagName('script');

$t = $scripts->item(0)->textContent;

$pat = "/\\{(.*?)\\}/";
preg_match($pat, $t, $matches);
$content = trim($matches[1]);
$content = trim($content, ';');
$content = trim($content, 'document.cookie=');
$arr = explode(';', $content);

$cookie_string = $arr[0];
$cookie_string = trim($cookie_string, '"');
$cookie_array = explode('=', $cookie_string);

$cookie_array_prepare = [$cookie_array[0] => $cookie_array[1]];

$host = parse_url($baseUrl, PHP_URL_HOST);
$cookieJar = CookieJar::fromArray($cookie_array_prepare, $host);

$response = $client->get("{$baseUrl}lvov/uk/x2004_02_03/x2016_05_18/x2017_11_24_1.html",
    [
        'headers' => [
            'User-Agent' => 'Tegos/1.1'
        ],
        'cookies' => $cookieJar
    ]
);

$dom = new IvoPetkov\HTML5DOMDocument();
$dom->loadHTML($response->getBody());
echo $dom->saveHTML();
