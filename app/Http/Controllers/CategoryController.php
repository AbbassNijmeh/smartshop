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
            'name' => 'required|string',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();
        return redirect()->back()->with('success', 'Category created successfully.');
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
}
}
