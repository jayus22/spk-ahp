<?php

namespace App\View\Components;

use App\Models\Comparison;
use App\Models\CalculationResult;
use Illuminate\View\Component;
use Illuminate\View\View;

class ResultTable extends Component
{
    /**
     * The comparison model.
     */
    public $comparison;
    
    /**
     * The calculation result.
     */
    public $result;
    
    /**
     * Whether to show detailed calculations.
     */
    public $showDetails;
    
    /**
     * Create the component instance.
     *
     * @param  \App\Models\Comparison  $comparison
     * @param  bool  $showDetails
     * @return void
     */
    public function __construct(Comparison $comparison, $showDetails = false)
    {
        $this->comparison = $comparison;
        $this->result = $comparison->calculationResult;
        $this->showDetails = $showDetails;
    }
    
    /**
     * Get total score for a laptop.
     *
     * @param  int  $laptopId
     * @return float
     */
    public function getTotalScore($laptopId)
    {
        if (!$this->result || !$this->result->result_data) {
            return 0;
        }
        
        $resultData = $this->result->result_data;
        
        return $resultData[$laptopId] ?? 0;
    }
    
    /**
     * Get individual criterion score for a laptop.
     *
     * @param  int  $laptopId
     * @param  int  $criteriaId
     * @return float
     */
    public function getCriteriaScore($laptopId, $criteriaId)
    {
        $score = $this->comparison->laptopScores()
            ->where('laptop_id', $laptopId)
            ->where('criteria_id', $criteriaId)
            ->first();
            
        return $score ? $score->score : 0;
    }
    
    /**
     * Get weighted score for a laptop by criteria.
     *
     * @param  int  $laptopId
     * @param  int  $criteriaId
     * @return float
     */
    public function getWeightedScore($laptopId, $criteriaId)
    {
        $score = $this->comparison->laptopScores()
            ->where('laptop_id', $laptopId)
            ->where('criteria_id', $criteriaId)
            ->first();
            
        if (!$score) {
            return 0;
        }
        
        $weight = $this->comparison->criteriaWeights()
            ->where('criteria_id', $criteriaId)
            ->first();
            
        return $weight ? ($score->score * $weight->weight) : 0;
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.result-table');
    }
}