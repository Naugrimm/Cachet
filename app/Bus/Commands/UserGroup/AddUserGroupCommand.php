<?php

namespace CachetHQ\Cachet\Bus\Commands\UserGroup;

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
     * @var string[]
     */
    public $rules = [
        'name' => 'required|unique:user_groups',
    ];

    /**
     * Create a new user group instance.
     *
     * @param string $name
     *
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
