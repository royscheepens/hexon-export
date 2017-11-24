<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hexon IP Whitelist
    |--------------------------------------------------------------------------
    |
    | The IP addresses Hexon uses to POST data to your server are whitelisted
    | here. The whitelist is only checked when in production.
    |
    */

    'ip_whitelist' => [
        '82.94.237.8',
        '82.94.240.8'
    ],

    /*
     |--------------------------------------------------------------------------
     | Url Endpoint
     |--------------------------------------------------------------------------
     |
     | The url where the POST requests from Hexon are routed to.
     |
     */
    'url_endpoint' => '/hexon-export',

    /*
     |--------------------------------------------------------------------------
     | Images Storage Path
     |--------------------------------------------------------------------------
     |
     | The path where occasion images, relative to your 'public' storage disk.
     |
     */
    'images_storage_path' => 'occasions/images/',

    /*
     |--------------------------------------------------------------------------
     | XML Storage Path
     |--------------------------------------------------------------------------
     |
     | The path where incoming XML files are stored, relative to
     | your 'default' storage disk.
     |
     */
    'xml_storage_path' => 'hexon-export/xml/',
];
