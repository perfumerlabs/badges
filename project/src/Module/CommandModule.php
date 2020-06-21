<?php

namespace Badges\Module;

use Perfumer\Framework\Controller\Module;

class CommandModule extends Module
{
    public $name = 'badges';

    public $router = 'router.console';

    public $request = 'badges.request';
}