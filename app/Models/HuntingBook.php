<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use TestMonitor\Incrementable\Traits\Incrementable;

/**
 * Class HuntingBook
 * @package App\Models
 */
class HuntingBook extends Model
{
    use Incrementable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'hunting_book';

    /**
     * @var string
     */
    protected $incrementable = 'hunting_id';

    /**
     * @var string[]
     */
    protected $incrementableGroup = ['district_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'authorization_id',
        'district_id',
        'hunting_id',
        'start',
        'end',
        'shots',
        'description',
        'canceled',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start',
        'end',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    /**
     * Get a user from a given hunting.
     *
     * @return hasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get an authorization from a given hunting.
     *
     * @return hasOne
     */
    public function authorization(): HasOne
    {
        return $this->hasOne(Authorization::class, 'id', 'authorization_id');
    }

    /**
     * Get a district from a given hunting.
     *
     * @return hasOne
     */
    public function district(): HasOne
    {
        return $this->hasOne(District::class, 'id', 'district_id');
    }

    /**
     * Get a used hunting grounds from a given hunting.
     *
     * @return BelongsToMany
     */
    public function usedHuntingGrounds(): BelongsToMany
    {
        return $this->belongsToMany(District::class, 'used_hunting_grounds', 'hunting_book_id', 'hunting_ground_id');
    }

    /**
     * Get a hunted animals from a given hunting.
     *
     * @return HasMany
     */
    public function huntedAnimals(): HasMany
    {
        return $this->hasMany(HuntedAnimal::class, 'hunting_book_id');
    }
}
