<?php

use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Redis;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'demo4jgi5_xhap6z69vc0xs962161zzbsfm45_mjc0b0iilg4ac384fi4ommk6tx7p78me' => [
            'driver' => 'pgsql',
            'host' => 'pgm-d9jay82641z478b0eo.pgsql.ap-southeast-5.rds.aliyuncs.com',
            'port' => '5432',
            'database' => 'demo4jgi5_xhap6z69vc0xs962161zzbsfm45_mjc0b0iilg4ac384fi4ommk6tx7p78me',
            'username' => 'admin_mcd',
            'password' => 'KerjaMulu123',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],
];
