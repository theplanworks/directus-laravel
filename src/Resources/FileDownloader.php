<?php

namespace thePLAN\DirectusLaravel\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use thePLAN\DirectusLaravel\Http\ApiWrapper;

class FileDownloader
{
    public static function getFile($path)
    {
        $base_url = env('DIRECTUS_CMS_URL');
        $client = new Client();
        $req = new Request('GET', $base_url . $path);
        $resp = $client->send($req);
        if ($resp->getStatusCode() == 200) { // HTTP OK
            if ($resp->hasHeader('content-length')) {
                $contentLength = $resp->getHeader('content-length')[0];
            }
            $body = $resp->getBody();
            return $body;
        }
    }
}