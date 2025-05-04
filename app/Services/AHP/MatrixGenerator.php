<?php

namespace App\Services\AHP;

class MatrixGenerator
{
    /**
     * Generate comparison matrix from pairwise comparison data
     *
     * @param array $comparisonData - Format: [['criteria1_id' => 1, 'criteria2_id' => 2, 'value' => 5], ...]
     * @return array - The comparison matrix
     */
    public function generateComparisonMatrix(array $comparisonData): array
    {
        // Get all unique criteria IDs
        $criteriaIds = [];
        foreach ($comparisonData as $comparison) {
            $criteriaIds[] = $comparison['criteria1_id'];
            $criteriaIds[] = $comparison['criteria2_id'];
        }
        $criteriaIds = array_unique($criteriaIds);
        sort($criteriaIds);
        
        // Initialize matrix with 1's on diagonal
        $matrix = [];
        foreach ($criteriaIds as $row) {
            $matrix[$row] = [];
            foreach ($criteriaIds as $col) {
                $matrix[$row][$col] = ($row == $col) ? 1 : null;
            }
        }
        
        // Fill the matrix with comparison values
        foreach ($comparisonData as $comparison) {
            $row = $comparison['criteria1_id'];
            $col = $comparison['criteria2_id'];
            $value = $comparison['value'];
            
            $matrix[$row][$col] = $value;
            
            // Reciprocal value for the opposite comparison
            $matrix[$col][$row] = 1 / $value;
        }
        
        return $matrix;
    }
    
    /**
     * Normalize the comparison matrix
     *
     * @param array $matrix - The comparison matrix
     * @return array - The normalized matrix
     */
    public function normalizeMatrix(array $matrix): array
    {
        $normalizedMatrix = [];
        $columnSums = [];
        
        // Calculate the sum of each column
        foreach ($matrix as $row => $cols) {
            foreach ($cols as $col => $value) {
                if (!isset($columnSums[$col])) {
                    $columnSums[$col] = 0;
                }
                $columnSums[$col] += $value;
            }
        }
        
        // Normalize the matrix by dividing each element by its column sum
        foreach ($matrix as $row => $cols) {
            $normalizedMatrix[$row] = [];
            foreach ($cols as $col => $value) {
                $normalizedMatrix[$row][$col] = $value / $columnSums[$col];
            }
        }
        
        return $normalizedMatrix;
    }
    
    /**
     * Calculate the priority vector (criteria weights)
     *
     * @param array $normalizedMatrix - The normalized comparison matrix
     * @return array - The priority vector (criteria weights)
     */
    public function calculatePriorityVector(array $normalizedMatrix): array
    {
        $priorityVector = [];
        
        // Calculate the average of each row in the normalized matrix
        foreach ($normalizedMatrix as $row => $cols) {
            $rowSum = array_sum($cols);
            $priorityVector[$row] = $rowSum / count($cols);
        }
        
        return $priorityVector;
    }
}