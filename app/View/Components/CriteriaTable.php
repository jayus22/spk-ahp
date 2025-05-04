<?php

namespace App\View\Components;

use App\Models\Comparison;
use App\Models\Criteria;
use Illuminate\View\Component;
use Illuminate\View\View;

class CriteriaTable extends Component
{
    /**
     * The criteria collection.
     */
    public $criteria;
    
    /**
     * The comparison if available.
     */
    public $comparison;
    
    /**
     * Flag to enable editing.
     */
    public $editable;
    
    /**
     * Create the component instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|null  $criteria
     * @param  \App\Models\Comparison|null  $comparison
     * @param  bool  $editable
     * @return void
     */
    public function __construct($criteria = null, $comparison = null, $editable = false)
    {
        $this->criteria = $criteria ?? Criteria::all();
        $this->comparison = $comparison;
        $this->editable = $editable;
    }

    /**
     * Get the weights for criteria if comparison is available.
     */
    public function getWeights()
    {
        if (!$this->comparison) {
            return collect();
        }
        
        return $this->comparison->criteriaWeights()
            ->with('criteria')
            ->get()
            ->pluck('weight', 'criteria_id');
    }
    
    /**
     * Get criterion weight.
     */
    public function getWeight($criteriaId)
    {
        $weights = $this->getWeights();
        return $weights->get($criteriaId, 0);
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.criteria-table', [
            'weights' => $this->getWeights(),
        ]);
    }
}