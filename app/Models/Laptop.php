<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'processor',
        'ram',
        'storage',
        'gpu',
        'display_size',
        'resolution',
        'weight',
        'battery_life',
        'price',
        'image',
        'description',
    ];

    /**
     * Get the scores associated with this laptop.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(LaptopScore::class);
    }

    /**
     * Get the calculation results associated with this laptop.
     */
    public function calculationResults(): HasMany
    {
        return $this->hasMany(CalculationResult::class);
    }
    
    /**
     * Get all scores for this laptop formatted as ['criteria_id' => score]
     */
    public function getScoresArray(): array
    {
        $scores = [];
        foreach ($this->scores as $score) {
            $scores[$score->criteria_id] = $score->value;
        }
        return $scores;
    }
}