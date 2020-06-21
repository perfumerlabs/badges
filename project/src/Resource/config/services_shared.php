<?php

return [
    'gateway' => [
        'shared' => true,
        'class' => 'Project\\Gateway',
        'arguments' => ['#application', '#gateway.http', '#gateway.console']
    ],

    'mongo.client' => [
        'shared'    => true,
        'class'     => 'MongoDB\\Client',
        'arguments' => ['@mongo/server'],
    ],

    'mongo.db' => [
        'shared' => true,
        'init'   => function (\Perfumer\Component\Container\Container $container, array $parameters = []) {
            return $container->get('mongo.client')->selectDatabase('MONGO_DATABASE');
        },
    ],
];