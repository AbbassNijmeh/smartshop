<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Allergy;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Auth;

class AllergyController extends Controller
{
    public function index()
    {
        $allergies = Allergy::with('ingredients')->get();
        $ingredients = Ingredient::all();
        return view('admin.allergies.index', compact('allergies', 'ingredients'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:allergies,name',
            'ingredients' => 'array'
        ]);

        $allergy = Allergy::create(['name' => $request->name]);
        $allergy->ingredients()->attach($request->ingredients);

        return redirect()->route('allergies.index')->with('success', 'Allergy added successfully.');
    }


    public function update(Request $request, Allergy $allergy)
    {
        $request->validate(['name' => 'required|unique:allergies,name,' . $allergy->id]);
        $allergy->update(['name' => $request->name]);
        $allergy->ingredients()->sync($request->ingredients);
        return redirect()->route('allergies.index')->with('success', 'Allergy updated successfully.');
    }

    public function destroy(Allergy $allergy)
    {
        $allergy->ingredients()->detach();
        $allergy->delete();
        return redirect()->route('allergies.index')->with('success', 'Allergy deleted successfully.');
    }
    function userAllergies()
    {
        $allergies = Allergy::all();
        $userAllergies = Auth::user()->allergies->pluck('id')->toArray(); // For pre-checking selected ones


        return view('site.allergies', compact('allergies', 'userAllergies'));
    }
    public function updateUserAllergies(Request $request)
    {
        $request->validate([
            'allergies' => 'array',
            'allergies.*' => 'exists:allergies,id',
        ]);

        Auth::user()->allergies()->sync($request->allergies ?? []); // Attach or detach
        return redirect()->route('user.allergies')->with('success', 'Allergies updated.');
    }
}
