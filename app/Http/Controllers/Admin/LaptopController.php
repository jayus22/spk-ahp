<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AuthController;
use App\Http\Requests\LaptopRequest;
use App\Models\Laptop;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaptopController extends AuthController
{
    /**
     * Display a listing of the laptops.
     */
    public function index()
    {
        $laptops = Laptop::orderBy('brand')->orderBy('model')->paginate(10);
        return view('admin.laptops.index', compact('laptops'));
    }

    /**
     * Show the form for creating a new laptop.
     */
    public function create()
    {
        $criteria = Criteria::all();
        return view('admin.laptops.create', compact(var_name: 'criteria'));
    }

    /**
     * Store a newly created laptop in storage.
     */
    public function store(LaptopRequest $request)
    {
        $validated = $request->validated();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('laptops', 'public');
            $validated['image'] = $path;
        }
        
        // Create laptop
        $laptop = Laptop::create($validated);
        
        // Process laptop scores
        $this->processLaptopScores($laptop, $request);
        
        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified laptop.
     */
    public function edit(Laptop $laptop)
    {
        $criteria = Criteria::all();
        $scores = $laptop->scores->pluck('value', 'criteria_id')->toArray();
        
        return view('admin.laptops.edit', data: compact('laptop', 'criteria', 'scores'));
    }

    /**
     * Update the specified laptop in storage.
     */
    public function update(LaptopRequest $request, Laptop $laptop)
    {
        $validated = $request->validated();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($laptop->image) {
                Storage::disk('public')->delete($laptop->image);
            }
            
            $path = $request->file('image')->store('laptops', 'public');
            $validated['image'] = $path;
        }
        
        // Update laptop
        $laptop->update($validated);
        
        // Process laptop scores
        $this->processLaptopScores($laptop, $request);
        
        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop berhasil diperbarui.');
    }

    /**
     * Remove the specified laptop from storage.
     */
    public function destroy(Laptop $laptop)
    {
        // Delete image if exists
        if ($laptop->image) {
            Storage::disk('public')->delete($laptop->image);
        }
        
        $laptop->delete();
        
        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop berhasil dihapus.');
    }
    
    /**
     * Process and store laptop scores for each criteria
     */
    private function processLaptopScores(Laptop $laptop, Request $request)
    {
        $criteria = Criteria::all();
        
        foreach ($criteria as $criterion) {
            $scoreKey = 'score_' . $criterion->id;
            
            if ($request->has($scoreKey)) {
                $rawValue = $request->input($scoreKey);
                
                // Calculate normalized value (0-1)
                // For simplicity, we're assuming scores are already normalized
                // In a real application, you might want to implement proper normalization
                // based on min-max values for each criteria across all laptops
                $normalizedValue = $rawValue;
                
                // Create or update score
                $laptop->scores()->updateOrCreate(
                    ['criteria_id' => $criterion->id],
                    [
                        'value' => $normalizedValue,
                        'raw_value' => $rawValue
                    ]
                );
            }
        }
    }
}