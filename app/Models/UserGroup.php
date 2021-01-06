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
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = ['name'];


}
