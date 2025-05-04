<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaptopScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'comparison_id',
        'laptop_id',
        'criteria_id',
        'score', // Normalized score (0-1) for this criteria
        'raw_score', // Original score value
        'comparison_data', // JSON data for pairwise comparisons against other laptops
    ];

    protected $casts = [
        'score' => 'float',
        'raw_score' => 'float',
        'comparison_data' => 'array',
    ];

    /**
     * Get the comparison that owns the score.
     */
    public function comparison()
    {
        return $this->belongsTo(Comparison::class);
    }

    /**
     * Get the laptop that owns the score.
     */
    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }

    /**
     * Get the criteria this score is for.
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Get the final weighted score (score * criteria weight).
     */
    public function getFinalWeightedScoreAttribute()
    {
        $criteriaWeight = CriteriaWeight::where('comparison_id', $this->comparison_id)
            ->where('criteria_id', $this->criteria_id)
            ->first();

        return $criteriaWeight ? ($this->score * $criteriaWeight->weight) : 0;
    }
}