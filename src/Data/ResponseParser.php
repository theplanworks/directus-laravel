<?php

namespace thePLAN\DirectusLaravel\Data;

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
class ResponseParser
{
    /**
     * Parse Files
     *
     * @var boolean
     */
    public $parseFiles = false;

    /**
     * The column that holds the desired files
     *
     * @var string
     */
    public $fileColumn = '';

    /**
     * Create a new ResponseParser instance
     *
     * @param \thePLAN\DirectusLaravel\Http\ApiWrapper
     */
    public function __construct($apiWrapper = null)
    {
        $this->apiWrapper = ($apiWrapper == null) ? new ApiWrapper($this) : $apiWrapper;
    }

    /**
     * Given an object from the CMS, parse the data object
     *
     * @param  object $object The raw data from the CMS
     * @return object
     */
    public function parseData($object)
    {
        $output = [];

        if (isset($object->data)) // We have a top level
        {
            if(is_array($object->data))
            {
                foreach ($object->data as $key => $value)
                {
                    if (isset($value->active) && $value->active == 1)
                    {
                        $output[] = $this->parseValue($value);
                    }
                }
            }
            else if (isset($object->data->active) && $object->data->active == 1)
            {
                $output = $this->parseValue($object->data);
            }
        }

        return $output;
    }

    /**
     * Parse a single value from the CMS
     *
     * @param  object $object A value object from the CMS
     * @return object
     */
    public function parseValue($object)
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

    /**
     * Send a request to get a file object from the CMS
     *
     * @param  int $id The ID of the file object
     * @return object
     */
    public function getFile($id)
    {
        $url = 'files/' . $id;
        return $this->apiWrapper->SendRequest($url);
    }
}