<?php

namespace CachetHQ\Cachet\Models;

use Illuminate\Database\Eloquent\Model;


class UserGroup extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'name' => 'string',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'userGroupName' => 'required|unique:user_groups',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = ['userGroupName'];


}
