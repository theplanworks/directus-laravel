<?php

namespace thePLAN\DirectusLaravel;

use thePLAN\DirectusLaravel\Http\ApiWrapper;
use thePLAN\DirectusLaravel\Data\ResponseParser;
use thePLAN\DirectusLaravel\Resources\FileDownloader;
use Illuminate\Support\Facades\Cache;
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
    private $apiWrapper;
    private $parser;
    private $ttl;

    public function __construct()
    {
        $this->parser = new ResponseParser();
        $this->apiWrapper = new ApiWrapper($this->parser);
        $this->ttl = config('directus-laravel.time_to_live', 30);
    }

    public function getTableRows($table, $files = false, $file_col = '')
    {
        $this->parser->parseFiles = $files;
        $this->parser->fileColumn = $file_col;

        $url = 'tables/' . $table . '/rows';

        return Cache::remember($url, $this->ttl, function () use ($url) {
            return $this->apiWrapper->SendRequest($url);
        });
    }

    public function getTableRow($table, $id, $files = false, $file_col = '')
    {
        $this->parser->parseFiles = $files;
        $this->parser->fileColumn = $file_col;

        $url = 'tables/' . $table . '/rows/' . $id;

        return Cache::remember($url, $this->ttl, function () use ($url) {
            return $this->apiWrapper->SendRequest($url);
        });
    }

    public function getFile($filePath)
    {
        return FileDownloader::getFile($filePath);
    }
}