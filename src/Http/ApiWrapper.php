<?php

namespace thePLAN\DirectusLaravel\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use thePLAN\DirectusLaravel\Data\ResponseParser;

/**
 *
 * Laravel wrapper for Directus API
 *
 * @category   Laravel Directus
 * @version    1.0.0
 * @package    theplanworks/directus-laravel
 * @copyright  Copyright (c) 2017 thePLAN (http://www.theplanworks.com)
 * @author     Matt Fox <matt.fox@theplanworks.com>
 * @license    https://opensource.org/licenses/MIT    MIT
 */
class ApiWrapper
{
    /**
     * The ResponseParser instance
     *
     * @var \thePLAN\DirectusLaravel\Data\ResponseParser
     */
    private $parser;

    /**
     * The CURL client
     *
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * The base CMS URL
     *
     * @var string
     */
    private $base_url;

    /**
     * Create a new ApiWrapper instance
     *
     * @param \thePLAN\DirectusLaravel\Data\ResponseParser
     */
    public function __construct($parser = null)
    {
        $this->parser   = ($parser == null) ? new ResponseParser() : $parser;
        $this->client   = new Client();
        $this->base_url = config('directus-laravel.base_cms_url', 'https://database.account.directus.io') . '/api/1.1/';
    }

    /**
     * Create a Guzzle Request
     *
     * @param string $params Any desired extra parameters
     */
    private function CreateRequest($params = "")
    {
        return new Request('GET', $this->base_url . $params, [
            'Authorization' => 'Bearer ' . config('directus-laravel.api_key', '')
        ]);
    }

    /**
     * Send a Guzzle Request
     *
     * @param string $url The URL to send the request to
     */
    public function SendRequest($url)
    {
        $req  = $this->CreateRequest($url);
        $resp = $this->client->send($req);

        if ($resp != null)
        {
            $body = $resp->getBody()->getContents();
            if (!empty($body))
            {
                $json = json_decode($body);
                return $this->parser->parseData($json);
            }
        }

        return null;
    }
}