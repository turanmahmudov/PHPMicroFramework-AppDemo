<?php

return [
    'log_table_name' => 'phoenix_log',
    'migration_dirs' => [
        'first' => __DIR__ . '/resources/migrations',
    ],
    'environments' => [
        'local' => [
            'adapter' => getenv('DB_CONNECTION') ?: 'mysql',
            'host' => getenv('DB_HOST') ?: '0.0.0.0',
            'port' => getenv('DB_PORT') ?: 3306,
            'username' => getenv('DB_USERNAME') ?: 'admin',
            'password' => getenv('DB_PASSWORD') ?: 'password',
            'db_name' => getenv('DB_DATABASE') ?: 'simplerestdemo',
            'charset' => 'utf8mb4',
        ],
        'production' => [
            'adapter' => getenv('DB_CONNECTION') ?: 'mysql',
            'host' => getenv('DB_HOST') ?: '0.0.0.0',
            'port' => getenv('DB_PORT') ?: 3306,
            'username' => getenv('DB_USERNAME') ?: 'admin',
            'password' => getenv('DB_PASSWORD') ?: 'password',
            'db_name' => getenv('DB_DATABASE') ?: 'simplerestdemo',
            'charset' => 'utf8mb4',
        ],
    ],
    'default_environment ' => 'local',
];