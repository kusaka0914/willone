<?php

return [
    'indeed_woa' => [
        'host'     => env('INDEED_FTP_HOST'),
        'username' => env('INDEED_FTP_USERNAME'),
        'password' => env('INDEED_FTP_PASSWORD'),
        'port'     => env('INDEED_FTP_PORT'),
        'filename' => 'WOA_indeed',
        'protocol' => 'sftp',
    ],
    'stanby_woa' => [
        'host'     => env('STANBY_FTP_HOST'),
        'username' => env('STANBY_FTP_USERNAME'),
        'password' => env('STANBY_FTP_PASSWORD'),
        'port'     => env('STANBY_FTP_PORT'),
        'filename' => 'WOA_stanby_org',
        'protocol' => 'sftp',
    ],
    'stanby_ad_woa' => [
        'host'     => env('STANBY_FTP_HOST'),
        'username' => env('STANBY_FTP_USERNAME'),
        'password' => env('STANBY_FTP_PASSWORD'),
        'port'     => env('STANBY_FTP_PORT'),
        'filename' => 'WOA_stanby_ad',
        'protocol' => 'sftp',
    ],
    'kyujinbox_woa' => [
        'host'     => env('KYUJINBOX_FTP_HOST'),
        'username' => env('KYUJINBOX_FTP_USERNAME'),
        'password' => env('KYUJINBOX_FTP_PASSWORD'),
        'port'     => env('KYUJINBOX_FTP_PORT'),
        'filename' => 'WOA_Kyujinbox',
        'protocol' => 'ftp',
    ],
];
