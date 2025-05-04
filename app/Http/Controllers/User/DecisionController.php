<?php

namespace App\Http\Controllers\User;

use App\Models\Laptop;
use App\Models\Criteria;
use App\Models\Comparison;
use App\Models\CalculationResult;
use App\Services\AHP\AHPService;
use App\Services\AHP\MatrixGenerator;
use App\Services\AHP\ConsistencyChecker;
use App\Services\AHP\ScoreCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DecisionController extends Controller
{
    protected $ahpService;
    
    public function __construct()
    {
        $this->ahpService = new AHPService(
            new MatrixGenerator(),
            new ConsistencyChecker(),
            new ScoreCalculator()
        );
    }
    
    /**
     * Show the criteria comparison form.
     */
    public function showCriteriaForm()
    {
        $criteria = Criteria::all();
        
        // Generate pairs of criteria for comparison
        $pairs = [];
        $criteria_array = $criteria->toArray();
        
        for ($i = 0; $i < count($criteria_array); $i++) {
            for ($j = $i + 1; $j < count($criteria_array); $j++) {
                $pairs[] = [
                    'first' => $criteria_array[$i],
                    'second' => $criteria_array[$j]
                ];
            }
        }
        
        // Get user's previous comparisons if any
        $userId = Auth::user()->id;
        $comparisons = Comparison::where('user_id', $userId)->get()
            ->mapWithKeys(function ($item) {
                return ["comparison_{$item->criteria1_id}_{$item->criteria2_id}" => $item->value];
            })->toArray();
        
        return view('user.decision-form', compact('criteria', 'pairs', 'comparisons'));
    }
    
    /**
     * Process the decision and show results.
     */
    public function processDecision(Request $request)
    {
        $userId = Auth::user()->id;
        
        // Store comparisons
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'comparison_') === 0) {
                list($first, $second) = explode('_', str_replace('comparison_', '', $key));
                
                Comparison::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'criteria1_id' => $first,
                        'criteria2_id' => $second
                    ],
                    ['value' => $value]
                );
            }
        }
        
        // Get all comparisons for AHP calculation
        $comparisons = Comparison::where('user_id', $userId)->get();
        $comparisonData = [];
        foreach ($comparisons as $comparison) {
            $comparisonData[] = [
                'criteria1_id' => $comparison->criteria1_id,
                'criteria2_id' => $comparison->criteria2_id,
                'value' => $comparison->value
            ];
        }
        
        // Get all laptops with their scores
        $laptops = Laptop::with('scores')->get();
        $laptopScores = [];
        
        foreach ($laptops as $laptop) {
            $laptopScores[$laptop->id] = $laptop->getScoresArray();
        }
        
        // Perform AHP calculation
        $result = $this->ahpService->calculateDecision($comparisonData, $laptopScores);
        
        // Save calculation results
        $this->saveCalculationResults($userId, $result);
        
        // Prepare data for view
        $rankedLaptops = [];
        foreach ($result['ranking'] as $rank => $laptopId) {
            $laptop = $laptops->firstWhere('id', $laptopId);
            if ($laptop) {
                $rankedLaptops[] = [
                    'rank' => $rank + 1,
                    'laptop' => $laptop,
                    'score' => $result['final_scores'][$laptopId]
                ];
            }
        }
        
        $criteriaWeights = $result['criteria_weights'];
        $consistencyRatio = $result['consistency_ratio'];
        $isConsistent = $result['is_consistent'];
        
        return view('results.show', compact(
            'rankedLaptops', 
            'criteriaWeights', 
            'consistencyRatio', 
            'isConsistent'
        ));
    }
    
    /**
     * Show history of previous calculations.
     */
    public function showHistory()
    {
        $userId = Auth::user()->id;
        
        $calculationResults = CalculationResult::where('user_id', $userId)
            ->with('laptop')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('created_at')
            ->map(function ($group) {
                return $group->sortBy('rank');
            });
        
        return view('results.history', compact('calculationResults'));
    }
    
    /**
     * Save calculation results to database.
     */
    private function saveCalculationResults($userId, $result)
    {
        // Delete previous results for this user
        CalculationResult::where('user_id', $userId)->delete();
        
        // Save new results
        foreach ($result['ranking'] as $rank => $laptopId) {
            CalculationResult::create([
                'user_id' => $userId,
                'laptop_id' => $laptopId,
                'final_score' => $result['final_scores'][$laptopId],
                'rank' => $rank + 1,
                'criteria_contributions' => json_encode($this->calculateCriteriaContributions(
                    $laptopId, 
                    $result['criteria_weights']
                )),
                'calculation_details' => json_encode([
                    'criteria_weights' => $result['criteria_weights']
                ]),
                'consistency_ratio' => $result['consistency_ratio']
            ]);
        }
    }
    
    /**
     * Calculate contribution of each criteria to final score.
     */
    private function calculateCriteriaContributions($laptopId, $criteriaWeights)
    {
        $laptop = Laptop::find($laptopId);
        $scores = $laptop->getScoresArray();
        $contributions = [];
        
        foreach ($scores as $criteriaId => $score) {
            if (isset($criteriaWeights[$criteriaId])) {
                $contributions[$criteriaId] = $score * $criteriaWeights[$criteriaId];
            }
        }
        
        return $contributions;
    }
}