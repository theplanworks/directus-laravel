<?php namespace thePLAN\DirectusLaravel;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

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
    private $parseFiles = false;
    private $fileColumn = '';

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

    private function SendRequest($url)
    {
        $req = $this->CreateRequest($url);
        $resp = $this->client->send($req);

        if ($resp != null)
        {
            $body = $resp->getBody()->getContents();
            if (!empty($body))
            {
                $json = json_decode($body);
                return $this->parseData($json);
            }
        }

        return null;
    }

    private function parseData($object)
    {
        $output = [];

        if (isset($object->data)) // We have a top level
        {
            if(is_array($object->data))
            {
                foreach ($object->data as $key => $value)
                {
                    $output[] = $this->parseValue($value);
                }
            }
            else
            {
                $output = $this->parseValue($object->data);
            }
        }

        return $output;
    }

    private function parseValue($object)
    {
        $output = [];

        foreach ($object as $key => $value)
        {
            if (is_object($value))
            {
                $output[$key] = $this->parseData($value);
            }
            else if($this->parseFiles && $this->fileColumn == $key)
            {
                $output[$key] = $this->getFile($value);
            }
            else
            {
                $output[$key] = $value;
            }
        }

        return (object) $output;
    }

    public function getTableRows($table, $files = false, $file_col = '')
    {
        $this->parseFiles = $files;
        $this->fileColumn = $file_col;

        $url = 'tables/' . $table . '/rows';
        return $this->SendRequest($url, $files, $file_col);
    }

    public function getTableRow($table, $id, $files = false, $file_col = '')
    {
        $this->parseFiles = $files;
        $this->fileColumn = $file_col;

        $url = 'tables/' . $table . '/rows/' . $id;
        return $this->SendRequest($url, $files, $file_col);
    }

    public function getFile($id)
    {
        $url = 'files/' . $id;
        return $this->SendRequest($url);
    }
}