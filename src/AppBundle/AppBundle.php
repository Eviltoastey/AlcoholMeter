<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseUser;

class AppBundle extends BaseUser
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
