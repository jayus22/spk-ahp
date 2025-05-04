<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_benefit', // Boolean: true if higher values are better, false if lower values are better
        'weight',
    ];

    /**
     * Get the laptop scores associated with this criteria.
     */
    public function laptopScores(): HasMany
    {
        return $this->hasMany(LaptopScore::class);
    }

    /**
     * Get the comparisons where this criteria is the first criteria.
     */
    public function comparisonsAsFirst(): HasMany
    {
        return $this->hasMany(Comparison::class, 'criteria1_id');
    }

    /**
     * Get the comparisons where this criteria is the second criteria.
     */
    public function comparisonsAsSecond(): HasMany
    {
        return $this->hasMany(Comparison::class, 'criteria2_id');
    }
}