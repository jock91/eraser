<?php

namespace Erazr\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ErazrUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
