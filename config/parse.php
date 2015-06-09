<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Keys
    |--------------------------------------------------------------------------
    |
    | You can find these keys at https://parse.com/apps/{YOURAPP}/edit#keys
    |
    | It's probably a good idea to store these in your .env file, so that
    | they are not in your version control.
    |
    */

    'app_id'     => env('PARSE_APP_ID', ''),
    'rest_key'   => env('PARSE_REST_KEY', ''),
    'master_key' => env('PARSE_MASTER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Subclasses
    |--------------------------------------------------------------------------
    |
    | If you'd like to provide custom subclasses for your parse classes, you
    | can generate them with:
    |
    |     php artisan parse:subclass ClassName [--parse-class=ParseClassName]
    |
    | Then you must register them here
    |
    */

    'subclasses' => [
        // '\App\ParseClasses\CustomClass'
    ],


    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | Once you add a new repository, you should add it to this array so that
    | you can inject the contract into your constructors thereby "insulating"
    | your classes from being tightly coupled to the Parse SDK. Generate the
    | repository with:
    |
    |     php artisan parse:repository ClassName [--parse-class=ParseClassName]
    |
    | Then you must register them here
    |
    */
    'repositories' => [
        // '\App\Repositories\ParseCustomClassRepository' => '\App\Repositories\Contracts\CustomClassRepository',
    ],
];
