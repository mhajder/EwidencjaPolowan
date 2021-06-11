<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class HuntedAnimal
 * @package App\Models
 */
class HuntedAnimal extends Model
{
    /**
     * @var string
     */
    protected $table = 'hunted_animals';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hunting_book_id',
        'animal_category_id',
        'animal_id',
        'purpose',
        'tag',
        'weight',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get an animal category from a given hunted animal.
     *
     * @return HasOne
     */
    public function animalCategory(): HasOne
    {
        return $this->hasOne(Animal::class, 'id', 'animal_category_id');
    }

    /**
     * Get an animal from a given hunted animal.
     *
     * @return HasOne
     */
    public function animal(): HasOne
    {
        return $this->hasOne(Animal::class, 'id', 'animal_id');
    }
}
