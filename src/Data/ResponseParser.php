<?php

namespace thePLAN\DirectusLaravel\Data;

use thePLAN\DirectusLaravel\Http\ApiWrapper;

class ResponseParser
{
    public $parseFiles = false;
    public $fileColumn = '';

    public function __construct($apiWrapper = null)
    {
        $this->apiWrapper = ($apiWrapper == null) ? new ApiWrapper($this) : $apiWrapper;
    }

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

    public function getFile($id)
    {
        $url = 'files/' . $id;
        return $this->apiWrapper->SendRequest($url);
    }
}