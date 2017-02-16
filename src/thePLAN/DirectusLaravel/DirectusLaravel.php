<?php namespace thePLAN\DirectusLaravel;

use GuzzleHttp\Client;

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

    public __construct()
    {
        $this->client = new Client();
        $this->base_url = env('DIRECTUS_CMS_URL') . '/api/1.1/';
    }

    private function CreateRequest($params = "")
    {
        return $this->client->request('GET', $this->base_url . $params, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('DIRECTUS_API_KEY')
            ]
        ]);
    }

    public function getTableRows($table)
    {
        $req = $this->CreateRequest('tables/' . $table . '/rows');
        return $this->client->send($req);
    }
}