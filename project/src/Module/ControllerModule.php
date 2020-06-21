<?php

namespace Badges\Module;

use Perfumer\Framework\Controller\Module;

class ControllerModule extends Module
{
    public $name = 'badges';

    public $router = 'badges.router';

    public $request = 'badges.request';

    public $components = [
        'view' => 'view.status'
    ];
}