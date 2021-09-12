<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/resources/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/resources/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinx_log',
        'default_database' => 'development',
        'development' => [
            'adapter' => getenv('DB_CONNECTION') ?: 'mysql',
            'host' => getenv('DB_HOST') ?: '0.0.0.0',
            'port' => getenv('DB_PORT') ?: 3306,
            'user' => getenv('DB_USERNAME') ?: 'admin',
            'pass' => getenv('DB_PASSWORD') ?: 'password',
            'name' => getenv('DB_DATABASE') ?: 'simplerestdemo',
            'charset' => 'utf8mb4',
        ],
        'production' => [
            'adapter' => getenv('DB_CONNECTION') ?: 'mysql',
            'host' => getenv('DB_HOST') ?: '0.0.0.0',
            'port' => getenv('DB_PORT') ?: 3306,
            'user' => getenv('DB_USERNAME') ?: 'admin',
            'pass' => getenv('DB_PASSWORD') ?: 'password',
            'name' => getenv('DB_DATABASE') ?: 'simplerestdemo',
            'charset' => 'utf8mb4',
        ],
        'testing' => [
            'adapter' => 'sqlite',
            'name' => '/tmp/testing'
        ]
    ],
    'version_order' => 'creation'
];