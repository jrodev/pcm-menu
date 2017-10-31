<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

		'jsonPath' => __DIR__ . '/../data/',

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'env' => getenv('APP_ENV'),
        'socket-url' => (getenv('APP_ENV')=='production') ? 'https://socket-menu.herokuapp.com' : 'http://192.168.10.17:8686'
    ],
];
