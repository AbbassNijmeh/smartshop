<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function Adminindex()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.category.index', compact('categories'));
    }
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    try {
        $category = new Category();
        $category->name = $request->name;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->save();

        return redirect()->back()->with('success', 'Category created successfully.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error creating category: ' . $e->getMessage());
    }
}
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
