<?php

namespace App\Services\AHP;

use App\Models\LaptopScore;
use App\Models\Comparison;
use App\Models\Criteria;


class AHPService
{
    protected $matrixGenerator;
    protected $consistencyChecker;
    protected $scoreCalculator;

    public function __construct(
        MatrixGenerator $matrixGenerator,
        ConsistencyChecker $consistencyChecker,
        ScoreCalculator $scoreCalculator
    ) {
        $this->matrixGenerator = $matrixGenerator;
        $this->consistencyChecker = $consistencyChecker;
        $this->scoreCalculator = $scoreCalculator;
    }

    /**
     * Perform the complete AHP calculation process
     *
     * @param array $comparisonData - Array of pairwise comparisons between criteria
     * @param array $laptopScores - Array of laptop scores for each criteria
     * @return array - The final calculation results
     */
    public function calculateDecision(array $comparisonData, array $laptopScores): array
    {
        // Step 1: Generate the pairwise comparison matrix
        $comparisonMatrix = $this->matrixGenerator->generateComparisonMatrix($comparisonData);
        
        // Step 2: Normalize the comparison matrix
        $normalizedMatrix = $this->matrixGenerator->normalizeMatrix($comparisonMatrix);
        
        // Step 3: Calculate criteria weights (priority vector)
        $criteriaWeights = $this->matrixGenerator->calculatePriorityVector($normalizedMatrix);
        
        // Step 4: Check consistency
        $consistencyResults = $this->consistencyChecker->checkConsistency($comparisonMatrix, $criteriaWeights);
        
        // If consistency ratio is too high, we might want to warn the user
        $isConsistent = $consistencyResults['cr'] <= 0.1;
        
        // Step 5: Calculate final scores for each laptop
        $finalScores = $this->scoreCalculator->calculateFinalScores($laptopScores, $criteriaWeights);
        
        // Sort laptops by score in descending order
        arsort($finalScores);
        
        // Prepare and return the final results
        return [
            'criteria_weights' => $criteriaWeights,
            'consistency_ratio' => $consistencyResults['cr'],
            'is_consistent' => $isConsistent,
            'final_scores' => $finalScores,
            'ranking' => array_keys($finalScores)
        ];
    }
     public function processLaptopsMatrix(Comparison $comparison, Criteria $criteria, array $data)
    {
        foreach ($data as $laptopId => $score) {
            LaptopScore::updateOrCreate(
                [
                    'comparison_id' => $comparison->id,
                    'criteria_id' => $criteria->id,
                    'laptop_id' => $laptopId,
                ],
                [
                    'score' => $score
                ]
            );
        }
    }
     public function processCriteriaMatrix(Comparison $comparison, array $data)
    {
        // Logika pemrosesan matriks kriteria
    }
}