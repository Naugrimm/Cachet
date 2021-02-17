<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Commands\SpEmployees;

use CachetHQ\Cachet\Models\SpEmployees;

final class DeleteSpEmployeeCommand
{
    /**
     * The subscriber to unsubscribe.
     *
     * @var \CachetHQ\Cachet\Models\SpEmployees
     */
    public $employee;

    /**
     * Create a unsubscribe subscriber command instance.
     *
     * @param \CachetHQ\Cachet\Models\SpEmployees $employee
     *
     * @return void
     */
    public function __construct(SpEmployees $employee)
    {
        $this->employee = $employee;
    }
}
