<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Bus\Commands\ComponentGroup;

use CachetHQ\Cachet\Models\ComponentGroup;

/**
 * This is the update component group command.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class UpdateComponentGroupCommand
{
    /**
     * The component group.
     *
     * @var \CachetHQ\Cachet\Models\ComponentGroup
     */
    public $group;

    /**
     * The component group name.
     *
     * @var string
     */
    public $name;

    /**
     * The component group description.
     *
     * @var int
     */
    public $order;

    /**
     * Is the component group collapsed?
     *
     * @var int
     */
    public $collapsed;

    /**
     * Is the component group collapsed?
     *
     * @var null|int
     */
    public $user_groups_id;

    /**
     * Is the component visible to public?
     *
     * @var int
     */
    public $visible;

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'name'      => 'nullable|string',
        'order'     => 'nullable|int',
        'collapsed' => 'nullable|int|between:0,4',
        'visible'   => 'nullable|bool',
    ];

    /**
     * Create a add component group command instance.
     *
     * @param \CachetHQ\Cachet\Models\ComponentGroup $group
     * @param string                                 $name
     * @param int                                    $order
     * @param int                                    $collapsed
     * @param null|int                               $user_groups_id
     * @param int                                    $visible
     *
     * @return void
     */
    public function __construct(ComponentGroup $group, $name, $order, $collapsed, $user_groups_id, $visible)
    {
        $this->group = $group;
        $this->name = $name;
        $this->order = (int) $order;
        $this->collapsed = $collapsed;
        $this->user_groups_id = $user_groups_id;
        $this->visible = (int) $visible;
    }
}
