<?php

namespace App\Models;

use App\Helpers\Helper;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'pesel',
        'email',
        'street',
        'house_number',
        'zip_code',
        'city',
        'phone',
        'password',
        'permission',
        'selected_district',
        'disabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
     * Get a selected district from a given user.
     *
     * @return HasOne
     */
    public function selectedDistrict(): HasOne
    {
        return $this->hasOne(District::class, 'id', 'selected_district');
    }

    /**
     * Get the user's valid authorizations in given district.
     *
     * @param $district_id
     * @return HasMany
     */
    public function validAuthorizationsInGivenDistrict($district_id): HasMany
    {
        $nowDatabaseFormat = date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()));
        return $this->authorizationsInGivenDistrict($district_id)
            ->where('valid_from', '<=', $nowDatabaseFormat)
            ->where('valid_until', '>=', $nowDatabaseFormat);
    }

    /**
     * Get the user's authorizations in given district.
     *
     * @param $district_id
     * @return HasMany
     */
    public function authorizationsInGivenDistrict($district_id): HasMany
    {
        return $this->hasMany(Authorization::class, 'user_id')
            ->where('district_id', '=', $district_id);
    }

    /**
     * Check if user is hunting in given district or if have planed hunting
     *
     * @param $district_id
     * @return bool
     */
    public function checkIfUserIsHuntingInGivenDistrict($district_id): bool
    {
        $nowDatabaseFormat = date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()));
        return $this->hasMany(HuntingBook::class, 'user_id')
            ->where('district_id', '=', $district_id)
            ->where('end', '>', $nowDatabaseFormat)
            ->where('canceled', '=', 0)
            ->exists();
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
