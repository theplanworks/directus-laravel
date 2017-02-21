<?php

return [

    /**
     * Cache time in minutes
     */
    'time_to_live' => 30,

    /**
     * Directus CMS Base URL
     * eg: https://database.account.directus.io
     */
    'base_cms_url' => env('DIRECTUS_CMS_URL', 'https://database.account.directus.io'),

    /**
     * API Key
     * -- HIGHLY RECOMMENDED THAT THIS BE PLACED IN .env FILE! --
     */
    'api_key' => env('DIRECTUS_API_KEY', '')

];