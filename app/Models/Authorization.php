<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Authorization
 * @package App\Models
 */
class Authorization extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'authorizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'number',
        'valid_from',
        'valid_until',
        'district_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'valid_from',
        'valid_until',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
