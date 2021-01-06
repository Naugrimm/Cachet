<?php

namespace CachetHQ\Cachet\Bus\Commands\UserGroups;

use CachetHQ\Cachet\Models\UserGroup;

/**
 * This is the subscribe subscriber command.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class DeleteUserGroupCommand
{
    /**
     * @var UserGroup
     */
    public $userGroup;


    public function __construct(UserGroup $userGroup)
    {
        $this->userGroup = $userGroup;
    }
}
