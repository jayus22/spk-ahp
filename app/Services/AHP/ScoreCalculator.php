<?php

namespace App\Services\AHP;

class ScoreCalculator
{
    /**
     * Calculate final scores for each laptop
     *
     * @param array $laptopScores - Format: ['laptop_id' => ['criteria_id' => score, ...], ...]
     * @param array $criteriaWeights - Format: ['criteria_id' => weight, ...]
     * @return array - Final scores for each laptop
     */
    public function calculateFinalScores(array $laptopScores, array $criteriaWeights): array
    {
        $finalScores = [];
        
        foreach ($laptopScores as $laptopId => $scores) {
            $finalScores[$laptopId] = 0;
            
            foreach ($scores as $criteriaId => $score) {
                // Make sure the criteria exists in our weights
                if (isset($criteriaWeights[$criteriaId])) {
                    // Multiply the laptop's score for this criteria by the criteria's weight
                    $finalScores[$laptopId] += $score * $criteriaWeights[$criteriaId];
                }
            }
        }
        
        return $finalScores;
    }
}