<?php

namespace Piwicms\System\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PiwicmsSystemUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
