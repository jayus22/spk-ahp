<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'comparison_id',
        'result_data', // JSON data of the calculation results
        'consistency_ratio',
        'is_consistent', // boolean to indicate if CR is acceptable
        'best_laptop_id',
        'calculation_details', // JSON data with calculation steps
    ];

    protected $casts = [
        'result_data' => 'array',
        'is_consistent' => 'boolean',
        'calculation_details' => 'array',
    ];

    /**
     * Get the comparison that owns the calculation result.
     */
    public function comparison()
    {
        return $this->belongsTo(Comparison::class);
    }

    /**
     * Get the best laptop from the calculation.
     */
    public function bestLaptop()
    {
        return $this->belongsTo(Laptop::class, 'best_laptop_id');
    }
}