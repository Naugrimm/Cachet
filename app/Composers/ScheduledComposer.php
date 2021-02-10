<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Composers;

use CachetHQ\Cachet\Models\Schedule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * This is the scheduled composer.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Connor S. Parks <connor@connorvg.tv>
 */
class ScheduledComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\Contracts\View\View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if(Auth::user()) {
            $scheduledMaintenance = Schedule::current()->orderBy('scheduled_at')->get();
        }elseif(isset($_SESSION['sp_employee'])) {

        }else {
            $scheduledMaintenance = Schedule::current()->where('user_groups_id', '=', 0)->orderBy('scheduled_at')->get();
        }

        $view->withScheduledMaintenance($scheduledMaintenance);
    }
}
