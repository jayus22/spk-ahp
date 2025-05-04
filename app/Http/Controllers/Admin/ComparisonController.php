<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ComparisonRequest;
use App\Models\Comparison;
use App\Models\Criteria;
use App\Models\CriteriaWeight;
use App\Models\Laptop;
use App\Models\LaptopScore;
use App\Models\CalculationResult;
use App\Services\AHP\AHPService;
use App\Services\AHP\ScoreCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Routing\Controller;

class ComparisonController extends Controller
{
    protected $ahpService;
    protected $scoreCalculator;

    public function __construct(AHPService $ahpService,ScoreCalculator $scoreCalculator)
    {
        $this->ahpService = $ahpService;
        $this->scoreCalculator = $scoreCalculator;

    }
    public function index()
    {
        $comparisons = Comparison::with(['user', 'calculationResult'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.comparisons.index', compact('comparisons'));
    }

    public function create()
    {
        $criteria = Criteria::all();
        $laptops = Laptop::all();

        return view('admin.comparisons.create', compact('criteria', 'laptops'));
    }

    public function store(ComparisonRequest $request)
    {
        $comparison = Comparison::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'draft',
        ]);

        if ($request->has('criteria')) {
            foreach ($request->criteria as $criteriaId) {
                CriteriaWeight::create([
                    'comparison_id' => $comparison->id,
                    'criteria_id' => $criteriaId,
                    'weight' => 0,
                ]);
            }
        }

        if ($request->has('laptops')) {
            foreach ($request->laptops as $laptopId) {
                foreach ($request->criteria as $criteriaId) {
                    LaptopScore::create([
                        'comparison_id' => $comparison->id,
                        'laptop_id' => $laptopId,
                        'criteria_id' => $criteriaId,
                        'score' => 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.comparisons.edit', $comparison)
            ->with('success', 'Comparison created successfully.');
    }

    public function show(Comparison $comparison)
    {
        $comparison->load(['criteriaWeights.criteria', 'laptopScores.laptop', 'laptopScores.criteria', 'calculationResult']);

        return view('admin.comparisons.show', compact('comparison'));
    }

    public function edit(Comparison $comparison)
    {
        $comparison->load(['criteriaWeights.criteria', 'laptopScores.laptop', 'laptopScores.criteria']);
        $criteria = Criteria::all();
        $laptops = Laptop::all();

        return view('admin.comparisons.edit', compact('comparison', 'criteria', 'laptops'));
    }

    public function update(ComparisonRequest $request, Comparison $comparison)
    {
        $comparison->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.comparisons.edit', $comparison)
            ->with('success', 'Comparison updated successfully.');
    }

    public function destroy(Comparison $comparison)
    {
        $comparison->delete();

        return redirect()->route('admin.comparisons.index')
            ->with('success', 'Comparison deleted successfully.');
    }

    public function criteriaMatrix(Comparison $comparison)
    {
        $comparison->load('criteriaWeights.criteria');
        $criteria = $comparison->criteriaWeights->pluck('criteria');

        return view('admin.comparisons.criteria-matrix', compact('comparison', 'criteria'));
    }

    public function updateCriteriaMatrix(Request $request, Comparison $comparison)
    {
        $comparisonData = $request->input('comparison', []);

        $this->ahpService->processCriteriaMatrix($comparison, $comparisonData);

        return redirect()->route('admin.comparisons.laptops-matrix', $comparison)
            ->with('success', 'Criteria weights updated successfully.');
    }

    public function laptopsMatrix(Comparison $comparison)
    {
        $comparison->load(['criteriaWeights.criteria', 'laptopScores.laptop']);
        $criteria = $comparison->criteriaWeights->pluck('criteria');
        $laptops = $comparison->laptopScores->pluck('laptop')->unique();

        return view('admin.comparisons.laptops-matrix', compact('comparison', 'criteria', 'laptops'));
    }

    public function updateLaptopsMatrix(Request $request, Comparison $comparison, Criteria $criteria)
    {
        $comparisonData = $request->input('comparison', []);

        $this->ahpService->processLaptopsMatrix($comparison, $criteria, $comparisonData);

        $nextCriteria = $comparison->criteriaWeights()
            ->with('criteria')
            ->whereHas('criteria', function ($query) use ($criteria) {
                $query->where('id', '>', $criteria->id);
            })
            ->orderBy('criteria_id')
            ->first();

        if ($nextCriteria) {
            return redirect()->route('admin.comparisons.laptops-matrix', [
                'comparison' => $comparison,
                'criteria' => $nextCriteria->criteria
            ])->with('success', 'Laptop scores updated successfully.');
        }

        $this->calculateResults($comparison);

        return redirect()->route('admin.comparisons.results', $comparison)
            ->with('success', 'All comparisons completed successfully.');
    }

    protected function calculateResults(Comparison $comparison)
    {
        $result = $this->scoreCalculator->calculateFinalScores(
            $comparison->laptopScores->pluck('laptop.id')->toArray(),
            $comparison->criteriaWeights->pluck('weight')->toArray()
        );

        CalculationResult::updateOrCreate(
            ['comparison_id' => $comparison->id],
            ['result' => json_encode($result)]
        );
    }

    public function results(Comparison $comparison)
    {
        $comparison->load(['calculationResult', 'laptopScores.laptop', 'criteriaWeights.criteria']);

        if (!$comparison->calculationResult) {
            return redirect()->route('admin.comparisons.index')
                ->with('error', 'Final result not found. Please complete the comparisons first.');
        }

        $results = json_decode($comparison->calculationResult->result, true);

        return view('admin.comparisons.results', compact('comparison', 'results'));
    }
}