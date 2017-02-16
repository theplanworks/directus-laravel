<?php namespace thePLAN\DirectusLaravel;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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
class DirectusLaravel
{
    private $client;
    private $base_url;

    public function __construct()
    {
        $this->client = new Client();
        $this->base_url = env('DIRECTUS_CMS_URL') . '/api/1.1/';
    }

    private function CreateRequest($params = "")
    {
        return new Request('GET', $this->base_url . $params, [
            'Authorization' => 'Bearer ' . env('DIRECTUS_API_KEY')
        ]);
    }

    public function getTableRows($table)
    {
        $req = $this->CreateRequest('tables/' . $table . '/rows');
        return $this->client->send($req)->getBody()->getContents();
    }
}