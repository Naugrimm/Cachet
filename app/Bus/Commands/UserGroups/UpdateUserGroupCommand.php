<?php

namespace CachetHQ\Cachet\Bus\Commands\UserGroups;

use CachetHQ\Cachet\Models\UserGroup;

/**
 * This is the subscribe subscriber command.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class UpdateUserGroupCommand
{
    /**
     * The name of the user group.
     *
     * @var string
     */
    public $name;

    /**
     * @var UserGroup
     */
    public $userGroup;

    /**
     * The validation rules.
     *
     * @var array
     */
    public $rules = [
        'name' => 'required|unique:user_groups|min:3',
    ];



    public function __construct(UserGroup $userGroup, string $userGroupName)
    {
        $this->userGroup = $userGroup;
        $this->name = $userGroupName;
    }
}
