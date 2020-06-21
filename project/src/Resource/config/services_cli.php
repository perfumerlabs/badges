<?php

return [
    'badges.request' => [
        'class' => 'Perfumer\\Framework\\Proxy\\Request',
        'arguments' => ['$0', '$1', '$2', '$3', [
            'prefix' => 'Badges\\Command',
            'suffix' => 'Command'
        ]]
    ]
];
