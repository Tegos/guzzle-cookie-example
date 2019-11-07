<?php

namespace Application;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Sunra\PhpSimple\HtmlDomParser;

class Application
{
    public $base_url = 'https://www.mzv.cz/';
    public $path = 'lvov/uk/x2004_02_03/x2016_05_18/x2017_11_24_1.html';
    private $user_agent = 'Tegos/1.1';
    private $client;
    private $cookies;
    private $selector_of_code = '#content > div > div.article_content > div > ol > li > ul > li:nth-child(3) > strong';

    public function __construct()
    {
        $this->client = new Client();
        $this->cookies = $this->getCookies();

        // update client
        $this->client = new Client(['cookies' => $this->cookies]);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        $response = $this->client->get($this->base_url . $this->path,
            [
                'headers' => [
                    'User-Agent' => $this->user_agent,
                ],
            ]
        );

        $dom = HtmlDomParser::str_get_html($response->getBody());

        $code_element = $dom->find($this->selector_of_code, 0);

        $code = '';
        if (!is_null($code_element)) {
            $code = $code_element->text();
        }

        return $code;
    }

    /**
     * @return CookieJar
     */
    private function getCookies()
    {
        $response = $this->client->get($this->base_url . $this->path,
            [
                'headers' => [
                    'User-Agent' => $this->user_agent,
                ],
            ]
        );

        $response_string = (string)$response->getBody();
        $dom = HtmlDomParser::str_get_html($response_string);

        $script = $dom->getElementByTagName('script');

        $script_content = $script->text();
        if (empty($script_content)) {
            $script_content = $script->find('text', 0)->text();
        }

        $pat = '/\\{(.*?)\\}/';
        preg_match($pat, $script_content, $matches);
        $content = trim($matches[1]);
        $content = trim($content, ';');
        $content = trim($content, 'document.cookie=');
        $arr = explode(';', $content);

        $cookie_string = $arr[0];
        $cookie_string = trim($cookie_string, '"');
        $cookie_array = explode('=', $cookie_string);

        $cookie_array_prepare = [$cookie_array[0] => $cookie_array[1]];

        $host = parse_url($this->base_url, PHP_URL_HOST);
        $cookie_jar = CookieJar::fromArray($cookie_array_prepare, $host);

        return $cookie_jar;
    }
}
