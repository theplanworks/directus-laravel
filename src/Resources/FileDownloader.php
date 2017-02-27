<?php

namespace thePLAN\DirectusLaravel\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use thePLAN\DirectusLaravel\Http\ApiWrapper;

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
class FileDownloader
{
    /**
     * Get a file from the CMS
     *
     * @param  string $path The file path
     * @return binary
     */
    public static function getFile($path)
    {
        $base_url = env('DIRECTUS_CMS_URL');
        $client   = new Client();
        $req      = new Request('GET', $base_url . $path);
        $resp     = $client->send($req);

        if ($resp->getStatusCode() == 200) { // HTTP OK
            if ($resp->hasHeader('content-length')) {
                $contentLength = $resp->getHeader('content-length')[0];
            }
            $body = $resp->getBody();
            return $body;
        }
    }
}