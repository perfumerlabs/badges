<?php

namespace Project;

use Perfumer\Framework\Gateway\CompositeGateway;

class Gateway extends CompositeGateway
{
    protected function configure(): void
    {
        $this->addModule('badges', 'BADGES_HOST', null, 'http');
        $this->addModule('badges', 'badges',      null, 'cli');
    }
}
