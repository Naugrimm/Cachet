<?php

namespace CachetHQ\Tests\Cachet\Models;

use AltThree\TestBench\ValidationTrait;
use CachetHQ\Cachet\Models\SpEmployees;
use CachetHQ\Tests\Cachet\AbstractTestCase;


class SpEmployeesTest extends AbstractTestCase
{
    use ValidationTrait;

    public function testValidation()
    {
        $this->checkRules(new SpEmployees());
    }
}
