<?php

namespace thePLAN\DirectusLaravel\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use thePLAN\DirectusLaravel\Data\ResponseParser;

class ApiWrapper
{
    private $parser;
    private $client;
    private $base_url;

    public function __construct($parser = null)
    {
        $this->parser = ($parser == null) ? new ResponseParser() : $parser;
        $this->client = new Client();
        $this->base_url = config('directus-laravel.base_cms_url', 'https://database.account.directus.io') . '/api/1.1/';
    }

    private function CreateRequest($params = "")
    {
        return new Request('GET', $this->base_url . $params, [
            'Authorization' => 'Bearer ' . config('directus-laravel.api_key', '')
        ]);
    }

    public function SendRequest($url)
    {
        $req = $this->CreateRequest($url);
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