<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'comparison_id',
        'criteria_id',
        'weight', // Normalized priority weight (0-1)
        'raw_weight', // Original weight value
        'comparison_data', // JSON data for pairwise comparisons
    ];

    protected $casts = [
        'weight' => 'float',
        'raw_weight' => 'float',
        'comparison_data' => 'array',
    ];

    /**
     * Get the comparison that owns the weight.
     */
    public function comparison()
    {
        return $this->belongsTo(Comparison::class);
    }

    /**
     * Get the criteria that this weight belongs to.
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    /**
     * Get the paired criteria weights for this comparison.
     * This will return all weight comparisons between this criteria and other criteria.
     */
    public function pairedWeights()
    {
        return $this->hasMany(CriteriaWeight::class, 'comparison_id', 'comparison_id')
            ->where('criteria_id', '!=', $this->criteria_id);
    }
}