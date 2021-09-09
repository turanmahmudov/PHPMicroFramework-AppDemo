<?php

declare(strict_types=1);

return [
    'db' => [
        'default' => getenv('DB_CONNECTION') ?: 'mysql',

        'connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'mysql',//getenv('DB_HOST') ?: '0.0.0.0',
                'port' => getenv('DB_PORT') ?: 3306,
                'database' => getenv('DB_DATABASE') ?: 'simplerestdemo',
                'username' => getenv('DB_USERNAME') ?: 'admin',
                'password' => getenv('DB_PASSWORD') ?: 'password',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'options' => [
                    // Turn off persistent connections
                    PDO::ATTR_PERSISTENT => false,
                    // Enable exceptions
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    // Emulate prepared statements
                    PDO::ATTR_EMULATE_PREPARES => true,
                    // Set default fetch mode to array
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    // Set character set
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ],
            ],
        ],
    ],
];
