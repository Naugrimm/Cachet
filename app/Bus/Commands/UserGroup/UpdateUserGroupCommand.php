<?php

namespace CachetHQ\Cachet\Bus\Commands\UserGroup;

use CachetHQ\Cachet\Models\User;
use CachetHQ\Cachet\Models\UserGroup;

/**
 * This is the subscribe subscriber command.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class UpdateUserGroupCommand
{
    /**
     * @var UserGroup
     */
    public $userGroup;

    /**
     * The name of the user group.
     *
     * @var string
     */
    public $userGroupName;
    
    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'userGroupName' => 'required|unique:user_groups',
    ];


    /**
     * Create a new subscribe subscriber command instance.
     *
     * @param UserGroup $userGroup
     * @param string $userGroupName
     *
     */
    public function __construct(UserGroup $userGroup, string $userGroupName)
    {
        $this->userGroup = $userGroup;
        $this->userGroupName = $userGroupName;
    }
}
