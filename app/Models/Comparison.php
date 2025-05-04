<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status', // 'draft', 'completed'
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the comparison.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the criteria weights for this comparison.
     */
    public function criteriaWeights()
    {
        return $this->hasMany(CriteriaWeight::class);
    }

    /**
     * Get the laptop scores for this comparison.
     */
    public function laptopScores()
    {
        return $this->hasMany(LaptopScore::class);
    }

    /**
     * Get the calculation result for this comparison.
     */
    public function calculationResult()
    {
        return $this->hasOne(CalculationResult::class);
    }

    /**
     * Get the laptops being compared.
     */
    public function laptops()
    {
        return $this->belongsToMany(Laptop::class, 'laptop_scores')
            ->withPivot('score')
            ->withTimestamps();
    }

    /**
     * Get the criteria being used in this comparison.
     */
    public function criteria()
    {
        return $this->belongsToMany(Criteria::class, 'criteria_weights')
            ->withPivot('weight')
            ->withTimestamps();
    }
}