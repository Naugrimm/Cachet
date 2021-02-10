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

use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\ComponentGroup;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * This is the components composer.
 *
 * @author James Brooks <james@alt-three.com>
 * @author Connor S. Parks <connor@connorvg.tv>
 */
class ComponentsComposer
{
    /**
     * The user session object.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $guard;

    /**
     * Creates a new components composer instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $guard
     *
     * @return void
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

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
            $componentGroups = $this->getVisibleGroupedComponents();
            $ungroupedComponents = Component::ungrouped()->orderBy('status', 'desc')->get();
        }elseif(isset($_SESSION['sp_employee'])) {

        }else {
            $componentGroups = $this->getVisibleGroupedComponents()->where('user_groups_id', '=', 0);
            $ungroupedComponents = Component::ungrouped()->where('user_groups_id', '=', 0)->orderBy('status', 'desc')->get();
        }


        $view->withComponentGroups($componentGroups)
            ->withUngroupedComponents($ungroupedComponents);
    }

    /**
     * Get visible grouped components.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getVisibleGroupedComponents()
    {
        $componentGroupsBuilder = ComponentGroup::query();
        if (!$this->guard->check()) {
            $componentGroupsBuilder->visible();
        }

        $usedComponentGroups = Component::grouped()->pluck('group_id');

        return $componentGroupsBuilder->used($usedComponentGroups)
            ->get();
    }
}
