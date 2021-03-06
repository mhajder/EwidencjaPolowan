<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class District
 * @package App\Models
 */
class District extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'districts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'disabled',
        'parent_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get an available hunting grounds from a given district.
     *
     * @return HasMany
     */
    public function availableHuntingGrounds(): HasMany
    {
        return $this->huntingGrounds()->where('disabled', '=', 0);
    }

    /**
     * Get a hunting grounds from a given district.
     *
     * @return HasMany
     */
    public function huntingGrounds(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
