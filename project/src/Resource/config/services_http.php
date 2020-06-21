<?php

return [
    'fast_router' => [
        'shared' => true,
        'init' => function(\Perfumer\Component\Container\Container $container) {
            return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
                $r->addRoute('POST', '/badge', 'badge.post');
                $r->addRoute('GET', '/badges', 'badges.get');
                $r->addRoute('DELETE', '/badges', 'badges.delete');
                $r->addRoute('GET', '/counters', 'counters.get');
            });
        }
    ],

    'badges.router' => [
        'shared' => true,
        'class' => 'Perfumer\\Framework\\Router\\Http\\FastRouteRouter',
        'arguments' => ['#gateway.http', '#fast_router', [
            'data_type' => 'json',
            'allowed_actions' => ['get', 'post', 'delete'],
        ]]
    ],

    'badges.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Badges\\Controller',
            'suffix' => 'Controller'
        ]]
    ]
];
