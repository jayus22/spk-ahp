<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Http\Requests\CriteriaRequest;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Auth;

class CriteriaController extends Controller
{
    /**
     * Display a listing of the criteria.
     */
    public function index()
    {
        $criteria = Criteria::all();
        return view('admin.criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new criteria.
     */
    public function create()
    {
        return view('admin.criteria.create');
    }

    /**
     * Store a newly created criteria in storage.
     */
    public function store(CriteriaRequest $request)
    {
        Criteria::create($request->validated());
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified criteria.
     */
    public function edit(Criteria $criterium)
    {
        return view('admin.criteria.edit', compact('criterium'));
    }

    /**
     * Update the specified criteria in storage.
     */
    public function update(CriteriaRequest $request, Criteria $criterium)
    {
        $criterium->update($request->validated());
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified criteria from storage.
     */
    public function destroy(Criteria $criterium)
    {
        $criterium->delete();
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }
    
    /**
     * Show form for criteria pairwise comparison.
     */
    public function showComparisonForm()
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
        
        return view('admin.criteria.comparison', compact('criteria', 'pairs'));
    }
    
    /**
     * Store criteria pairwise comparisons.
     */
    public function storeComparisons(Request $request)
    {
        $userId = Auth::user()->id;
        
        foreach ($request->all() as $key => $value) {
            // Check if the key is a comparison input
            if (strpos($key, 'comparison_') === 0) {
                // Extract criteria IDs from the input name
                list($first, $second) = explode('_', str_replace('comparison_', '', $key));
                
                // Store or update the comparison
                \App\Models\Comparison::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'criteria1_id' => $first,
                        'criteria2_id' => $second
                    ],
                    ['value' => $value]
                );
            }
        }
        
        // Recalculate criteria weights using AHP
        $this->recalculateCriteriaWeights($userId);
        
        return redirect()->route('admin.criteria.index')
            ->with('success', 'Perbandingan kriteria berhasil disimpan.');
    }
    
    /**
     * Recalculate criteria weights using AHP method.
     */
    private function recalculateCriteriaWeights($userId)
    {
        // Get all comparisons for this user
        $comparisons = \App\Models\Comparison::where('user_id', $userId)->get();
        
        // Format comparisons for AHP service
        $comparisonData = [];
        foreach ($comparisons as $comparison) {
            $comparisonData[] = [
                'criteria1_id' => $comparison->criteria1_id,
                'criteria2_id' => $comparison->criteria2_id,
                'value' => $comparison->value
            ];
        }
        
        // Use AHP service to calculate weights
        $matrixGenerator = new \App\Services\AHP\MatrixGenerator();
        $comparisonMatrix = $matrixGenerator->generateComparisonMatrix($comparisonData);
        $normalizedMatrix = $matrixGenerator->normalizeMatrix($comparisonMatrix);
        $criteriaWeights = $matrixGenerator->calculatePriorityVector($normalizedMatrix);
        
        // Update weights in the database
        foreach ($criteriaWeights as $criteriaId => $weight) {
            $criteria = Criteria::find($criteriaId);
            if ($criteria) {
                $criteria->weight = $weight;
                $criteria->save();
            }
        }
    }
}