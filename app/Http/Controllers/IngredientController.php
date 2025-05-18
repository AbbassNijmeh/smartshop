<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    /**
     * Display a listing of ingredients.
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json(['success' => true, 'ingredients' => $ingredients]);
    }

    /**
     * Store a new ingredient.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name|max:255',
        ]);

        $ingredient = Ingredient::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Ingredient created successfully.');
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient)
    {
        return response()->json(['success' => true, 'ingredient' => $ingredient]);
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|unique:ingredients,name,' . $ingredient->id . '|max:255',
        ]);

        $ingredient->update([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Ingredient updated successfully.');
    }

    /**
     * Remove the specified ingredient.
     */
    public function destroy(Ingredient $ingredient)
    {
        // Check if the ingredient is linked to any products or allergies
        if ($ingredient->products()->exists() || $ingredient->allergies()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete this ingredient because it is associated with products or allergies.');
        }

        // If not linked, delete the ingredient
        $ingredient->delete();
        return redirect()->back()->with('success', 'Ingredient deleted successfully.');
    }
}
