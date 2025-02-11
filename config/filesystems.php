<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
     */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
     */

    'cloud'   => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
     */

    'disks'   => [

        'local'     => [
            'driver' => 'local',
            'root'   => storage_path('app'),
        ],

        'upload'    => [
            'driver' => 'local',
            'root'   => public_path(),
        ],

        'public'    => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3'        => [
            'driver' => 's3',
            'key'    => env('S3_ACCESS_KEY', ''),
            'secret' => env('S3_SECRET_KEY', ''),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
        ],

        's3_static' => [
            'driver' => 's3',
            'key'    => env('S3_STATIC_ACCESS_KEY', ''),
            'secret' => env('S3_STATIC_SECRET_KEY', ''),
            'region' => env('S3_STATIC_REGION'),
            'bucket' => env('S3_STATIC_BUCKET'),
        ],

        's3_mail'   => [
            'driver' => 's3',
            'region' => env('S3_MAIL_REGION'),
            'bucket' => env('S3_MAIL_BUCKET'),
        ],

        's3_batch'  => [
            'driver' => 's3',
            'region' => env('S3_BATCH_REGION'),
            'bucket' => env('S3_BATCH_BUCKET'),
        ],

        's3_co_image'             => [
            'driver' => 's3',
            'region' => env('S3_CO_IMAGE_REGION'),
            'bucket' => env('S3_CO_IMAGE_BUCKET'),
        ],

        's3_gcp_data_share' => [
            'driver' => 's3',
            'key'    => env('AWS_S3_GCP_DATA_SHARE_ACCESS_KEY', ''),
            'secret' => env('AWS_S3_GCP_DATA_SHARE_SECRET_KEY', ''),
            'region' => env('AWS_S3_GCP_DATA_SHARE_REGION'),
            'bucket' => env('AWS_S3_GCP_DATA_SHARE_BUCKET'),
        ],
    ],

];
