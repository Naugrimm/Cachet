<?php

namespace CachetHQ\Tests\Cachet\Models;

use AltThree\TestBench\ValidationTrait;
use CachetHQ\Cachet\Models\AllowedGroups;
use CachetHQ\Tests\Cachet\AbstractTestCase;


class AllowedGroupsTest extends AbstractTestCase
{
    use ValidationTrait;

    public function testValidation()
    {
        $this->checkRules(new AllowedGroups());
    }
}
