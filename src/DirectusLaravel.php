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

    /**
     * Create a new DirectusLaravel instance
     *
     * @return  void
     */
    public function __construct()
    {
        $this->parser     = new ResponseParser();
        $this->apiWrapper = new ApiWrapper($this->parser);
        $this->ttl        = config('directus-laravel.time_to_live', 30);
    }

    /**
     * Return an entire table as an object
     *
     * @param  string  $table    The table name
     * @param  boolean $files    Parse files (default: false)
     * @param  string  $file_col The name of the file column to parse
     * @return object
     */
    public function getTableRows($table, $files = false, $file_col = '')
    {
        $url = 'tables/' . $table . '/rows';

        return $this->getData($url, $files, $file_col);
    }

    /**
     * Get a single row from a table
     *
     * @param  string  $table    The table name
     * @param  int     $id       The id of the desired row
     * @param  boolean $files    Parse files (default: false)
     * @param  string  $file_col The name of the file column to parse
     * @return object
     */
    public function getTableRow($table, $id, $files = false, $file_col = '')
    {
        $url = 'tables/' . $table . '/rows/' . $id;

        return $this->getData($url, $files, $file_col);
    }

    /**
     * Get a row by the slug property
     *
     * @param  string  $table    The table name
     * @param  string  $slug     The identifying slug
     * @param  boolean $files    Parse files (default: false)
     * @param  string  $file_col The name of the file column to parse
     * @return object
     */
    public function getTableRowBySlug($table, $slug, $files = false, $file_col = '')
    {
        $output = [];
        $url = 'tables/' . $table . '/rows';
        $rows = $this->getData($url, $files, $file_col);

        foreach($rows as $row)
        {
            if ($row->slug == $slug)
            {
                return $row;
            }
        }

        return $output;
    }

    /**
     * Helper function to make the API call
     *
     * @param  string  $url      The input URL
     * @param  boolean $files    Parse files (default: false)
     * @param  string  $file_col The name of the file column to parse
     * @return object
     */
    protected function getData($url, $files = false, $file_col = '')
    {
        $url .= '?status=1';
        $this->parser->parseFiles = $files;
        $this->parser->fileColumn = $file_col;

        return Cache::remember($url, $this->ttl, function () use ($url) {
            return $this->apiWrapper->SendRequest($url);
        });
    }

    /**
     * Get a file from the CMS
     *
     * @param  string $filePath The path of the file
     * @return binary
     */
    public function getFile($filePath)
    {
        return FileDownloader::getFile($filePath);
    }
}