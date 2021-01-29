<?php

namespace CachetHQ\Tests\Cachet\Models;

use AltThree\TestBench\ValidationTrait;
use CachetHQ\Cachet\Models\UserGroup;
use CachetHQ\Tests\Cachet\AbstractTestCase;


class UserGroupTest extends AbstractTestCase
{
    use ValidationTrait;

    public function testValidation()
    {
        $this->checkRules(new UserGroup());
    }
}
