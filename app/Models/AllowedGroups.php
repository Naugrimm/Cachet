<?php

namespace CachetHQ\Cachet\Models;

use AltThree\Validator\ValidatingTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AllowedGroups extends Model
{
    use ValidatingTrait;

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'users_id' => 'int',
        'user_groups_id'  => 'int',
        'sp_employees_id' => 'int'
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = [
        'sp_employees_id',
        'users_id',
        'user_groups_id',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'sp_employees_id' => 'nullable|int',
        'users_id' => 'nullable|int',
        'user_groups_id'  => 'nullable|int',
    ];

    /**
     * Get the subscriber relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the component relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function group()
    {
        return $this->hasOne(UserGroup::class, 'id', 'user_groups_id');
    }
}
