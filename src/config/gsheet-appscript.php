<?php

return [

    /**
     * If you want to disable the route for the log view page
     * 
     * available options: true, false
     * 
     * default: true
     */

    'is_route_enabled' => true, 
    
    
    /**
     * If you want to change the route prefix
     * 
     */
    'route_prefix' => 'onex',
    

    /**
     * If you want to change the route name
     * 
     * default: gsheet
     */
    'route_name' => 'gsheet',

    /**
     * If you want to add any middleware (s) to restrict the access
     * 
     * default: web
     */
    'route_middleware' => ['web'],

    /**
     * If you want to change the page heading
     *
     */
    'page_heading' => 'Google Sheet',


    /**
     * If you want to use a authentication process to access the system log information view page
     */
    'authentication' => [
        'is_enabled' => env('GSHEET_APPSCRIPT_AUTH_ENABLED', false),
        'login_id' => env('GSHEET_APPSCRIPT_LOGIN_ID', 'onexadmin'),
        'password' => env('GSHEET_APPSCRIPT_LOGIN_PASSWORD', 'onexpassword')
    ],

    /**
     * Google Sheet - App Script API Url
     *
     */
    'gsheet-api-url' => env('GSHEET_APPSCRIPT_API_URL', null),
];