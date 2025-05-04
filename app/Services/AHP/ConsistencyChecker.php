<?php

namespace App\Services\AHP;

class ConsistencyChecker
{
    /**
     * Random Consistency Index values for matrices of different sizes
     * According to Saaty's scale
     */
    protected $randomConsistencyIndex = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
        11 => 1.51,
        12 => 1.54,
        13 => 1.56,
        14 => 1.57,
        15 => 1.58
    ];
    
    /**
     * Check the consistency of the comparison matrix
     *
     * @param array $matrix - The comparison matrix
     * @param array $priorityVector - The priority vector (criteria weights)
     * @return array - Contains consistency index (CI), consistency ratio (CR), and lambda max
     */
    public function checkConsistency(array $matrix, array $priorityVector): array
    {
        $n = count($matrix);
        
        // Calculate weighted sum vector
        $weightedSumVector = $this->calculateWeightedSumVector($matrix, $priorityVector);
        
        // Calculate lambda values (dividing each weighted sum by the corresponding priority)
        $lambdaValues = [];
        foreach ($weightedSumVector as $criteriaId => $weightedSum) {
            $lambdaValues[$criteriaId] = $weightedSum / $priorityVector[$criteriaId];
        }
        
        // Calculate lambda max (average of lambda values)
        $lambdaMax = array_sum($lambdaValues) / $n;
        
        // Calculate Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        // Get Random Consistency Index (RI) for this matrix size
        $ri = $this->getRandomConsistencyIndex($n);
        
        // Calculate Consistency Ratio (CR)
        $cr = ($ri > 0) ? $ci / $ri : 0;
        
        return [
            'ci' => $ci,
            'ri' => $ri,
            'cr' => $cr,
            'lambda_max' => $lambdaMax
        ];
    }
    
    /**
     * Calculate the weighted sum vector
     *
     * @param array $matrix - The comparison matrix
     * @param array $priorityVector - The priority vector (criteria weights)
     * @return array - The weighted sum vector
     */
    protected function calculateWeightedSumVector(array $matrix, array $priorityVector): array
    {
        $weightedSumVector = [];
        
        foreach ($matrix as $row => $cols) {
            $weightedSumVector[$row] = 0;
            foreach ($cols as $col => $value) {
                $weightedSumVector[$row] += $value * $priorityVector[$col];
            }
        }
        
        return $weightedSumVector;
    }
    
    /**
     * Get the Random Consistency Index for a matrix of size n
     *
     * @param int $n - The size of the matrix
     * @return float - The Random Consistency Index
     */
    protected function getRandomConsistencyIndex(int $n): float
    {
        return $this->randomConsistencyIndex[$n] ?? 1.59; // Default value for larger matrices
    }
}