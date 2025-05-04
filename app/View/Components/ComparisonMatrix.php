<?php

namespace App\View\Components;

use App\Models\Comparison;
use App\Models\Criteria;
use Illuminate\View\Component;
use Illuminate\View\View;

class ComparisonMatrix extends Component
{
    /**
     * The comparison model.
     */
    public $comparison;
    
    /**
     * The criteria collection.
     */
    public $criteria;
    
    /**
     * The comparison type (criteria or laptop).
     */
    public $type;
    
    /**
     * The laptop ID if comparing laptops.
     */
    public $laptopId;
    
    /**
     * The criteria ID if comparing laptops.
     */
    public $criteriaId;
    
    /**
     * Create the component instance.
     *
     * @param  \App\Models\Comparison  $comparison
     * @param  string  $type  Either 'criteria' or 'laptop'
     * @param  int|null  $laptopId  Required if type is 'laptop'
     * @param  int|null  $criteriaId  Required if type is 'laptop'
     * @return void
     */
    public function __construct(Comparison $comparison, $type = 'criteria', $laptopId = null, $criteriaId = null)
    {
        $this->comparison = $comparison;
        $this->type = $type;
        $this->laptopId = $laptopId;
        $this->criteriaId = $criteriaId;
        
        // Load criteria or laptops based on type
        if ($type === 'criteria') {
            $this->criteria = $comparison->criteria;
        } elseif ($type === 'laptop') {
            $this->criteria = [$comparison->laptops];
        }
    }
    
    /**
     * Get the label for the comparison scale.
     *
     * @param  int  $value
     * @return string
     */
    public function getScaleLabel($value)
    {
        $labels = [
            1 => 'Equal importance',
            2 => 'Equal to moderate importance',
            3 => 'Moderate importance',
            4 => 'Moderate to strong importance',
            5 => 'Strong importance',
            6 => 'Strong to very strong importance',
            7 => 'Very strong importance',
            8 => 'Very strong to extreme importance',
            9 => 'Extreme importance',
        ];
        
        return $labels[$value] ?? 'Unknown';
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.comparison-matrix');
    }
}