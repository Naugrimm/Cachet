<?php

namespace CachetHQ\Cachet\Bus\Commands\UserGroups;

/**
 * This is the subscribe subscriber command.
 *
 * @author James Brooks <james@alt-three.com>
 */
final class AddUserGroupCommand
{
    /**
     * The name of the user group.
     *
     * @var string
     */
    public $name;

    /**
     * The validation rules.
     *
     * @var array
     */
    public $rules = [
        'name' => 'required|unique:user_groups',
    ];

    /**
     * Create a new subscribe subscriber command instance.
     *
     * @param string $userGroupName
     *
     */
    public function __construct(string $userGroupName)
    {
        $this->name = $userGroupName;
    }
}
