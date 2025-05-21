<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function Adminindex()
    {
        $products = Product::all();
        return view('admin.product.index', compact('products'));
    }
    public function AdminShow($id)
    {
        $product = Product::find($id);
        return view('admin.product.details', compact('product'));
    }
    public function create()
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('admin.product.create', compact('categories', 'ingredients'));
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate request data
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'cost_price' => 'required|numeric|min:0',
                'price' => 'required|numeric|min:0',
                'barcode' => 'nullable|string|unique:products,barcode',
                'stock_quantity' => 'required|integer|min:0',
                'brand' => 'nullable|string|max:255',
                'discount' => 'nullable|numeric|min:0|max:100',
                'discount_start' => 'nullable|date|',
                'discount_end' => 'nullable|date|after_or_equal:discount_start',
                'expiration_date' => 'nullable|date',
                'weight' => 'nullable|string|max:255',
                'dimensions' => 'nullable|string|max:255',
                'aisle' => 'nullable|string|max:255',
                'section' => 'nullable|string|max:255',
                'floor' => 'nullable|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'ingredients' => 'nullable|array',
                'ingredients.*' => 'required|string|distinct'
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('storage'), $imageName);
                $imagePath = $imageName;
            }
            // Create product
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'],
                'cost_price' => $validated['cost_price'],
                'price' => $validated['price'],
                'barcode' => $validated['barcode'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'brand' => $validated['brand'] ?? null,
                'discount' => $validated['discount'] ?? null,
                'discount_start' => $validated['discount_start'] ?? null,
                'discount_end' => $validated['discount_end'] ?? null,
                'expiration_date' => $validated['expiration_date'] ?? null,
                'weight' => $validated['weight'] ?? null,
                'dimensions' => $validated['dimensions'] ?? null,
                'aisle' => $validated['aisle'] ?? null,
                'section' => $validated['section'] ?? null,
                'floor' => $validated['floor'] ?? null,
                'image' => $imagePath,
            ]);

            // Process ingredients
            $ingredientIds = [];
            if (!empty($validated['ingredients'])) {
                foreach ($validated['ingredients'] as $ingredientName) {
                    $ingredient = Ingredient::firstOrCreate(
                        ['name' => trim($ingredientName)],
                        ['name' => trim($ingredientName)]
                    );
                    $ingredientIds[] = $ingredient->id;
                }

                $product->ingredients()->sync($ingredientIds);
            }

            DB::commit();

            return redirect()
                ->route('products.index')
                ->with('success', 'Product created successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded image if transaction failed
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $ingredients = Ingredient::all();
        return view('admin.product.edit', compact('product', 'categories', 'ingredients'));
    }
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'expiration_date' => 'nullable|date',
            'weight' => 'nullable|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'aisle' => 'nullable|string|max:255',
            'section' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ingredients' => 'nullable|array',
        ]);

        // Handle image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('storage'), $imageName);
            $imagePath = 'storage/' . $imageName;
        }


        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'cost_price' => $request->cost_price,
            'price' => $request->price,
            'barcode' => $request->barcode,
            'stock_quantity' => $request->stock_quantity,
            'brand' => $request->brand,
            'discount' => $request->discount,
            'discount_start' => $request->discount_start,
            'discount_end' => $request->discount_end,
            'expiration_date' => $request->expiration_date,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'aisle' => $request->aisle,
            'section' => $request->section,
            'floor' => $request->floor,
            'image' => $imagePath,
        ]);

        if ($request->has('ingredients')) {
            $ingredientNames = $request->ingredients;

            // Ensure all entered ingredients exist in the database
            $ingredientIds = [];
            foreach ($ingredientNames as $name) {
                $ingredient = Ingredient::firstOrCreate(['name' => $name]);
                $ingredientIds[] = $ingredient->id;
            }

            // Sync product ingredients
            $product->ingredients()->sync($ingredientIds);
        }


        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    public function restock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->stock_quantity += $request->quantity;
        $product->save();

        return redirect()->back()->with('success', 'Product restocked successfully.');
    }

    public function deletePic(Request $request)
    {
        $productId = $request->input('product_id');
        $imagePath = $request->input('image');

        // Find the product
        $product = Product::find($productId);

        // Check if the product exists and if the image exists
        if ($product && $imagePath) {
            // Delete the image file from storage
            Storage::delete($imagePath);

            // Optionally, update the product record to remove the image reference
            $product->image = null;
            $product->save();

            // Flash message for success
            return redirect()->route('products.index')->with('success', 'Image deleted successfully.');
        }

        // Flash message for error if not found
        return redirect()->route('products.index')->with('error', 'Product or image not found.');
    }
    public function getProductByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        Log::info('Barcode scanned: ' . $barcode);

        $product = Product::with('ingredients')->where('barcode', $barcode)->first();

        if ($product) {
            Log::info('Product found: ' . $product->name);

            // Check if discount is currently active
            $hasDiscount = $product->discount > 0 && now()->between($product->discount_start, $product->discount_end);
            $discountedPrice = $hasDiscount
                ? $product->price - ($product->price * ($product->discount / 100))
                : null;

            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discounted_price' => $discountedPrice,
                'brand' => $product->brand,
                'image_url' => $product->image,
                'ingredients' => $product->ingredients->pluck('name'),
                'reviews_count' => $product->reviews_count ?? $product->reviews()->count(),
            ]);
        } else {
            Log::warning('Product not found for barcode: ' . $barcode);
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
}
