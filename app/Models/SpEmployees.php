<?php

namespace CachetHQ\Cachet\Models;

use Illuminate\Database\Eloquent\Model;

class SpEmployees extends Model
{
    /**
     * List of attributes that have default values.
     *
     * @var string[]
     */
    protected $attributes = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var string[]
     */
    protected $casts = [
        'username'  => 'string',
        'firstname' => 'string',
        'lastname' => 'string',
    ];

    /**
     * The validation rules.
     *
     * @var string[]
     */
    public $rules = [
        'username' => 'nullable|string',
        'firstname' => 'nullable|string',
        'lastname'  => 'nullable|string',
    ];

    /**
     * The fillable properties.
     *
     * @var string[]
     */
    protected $fillable = ['username', 'firstname', 'lastname'];

    /**
     * Get the subscriptions relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allowedGroups()
    {
        return $this->hasMany(AllowedGroups::class, 'sp_employees_id');
    }
}
