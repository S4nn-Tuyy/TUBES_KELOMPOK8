<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->when(!Auth::check() || !Auth::user()->is_admin, function ($query) {
                $query->where('is_hidden', false);
            })
            ->when(Auth::check(), function ($query) {
                $query->orWhere('user_id', Auth::id());
            })
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to create a product.');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to create a product.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'condition' => 'required|in:new,used',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048'
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        
        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'condition' => $validated['condition'],
            'stock' => $validated['stock'],
            'image' => $imagePath
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        if ($product->is_hidden && (!Auth::check() || (!Auth::user()->is_admin && $product->user_id !== Auth::id()))) {
            return redirect()->route('products.index')
                ->with('error', 'Produk ini telah disembunyikan oleh admin.');
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to edit products.');
        }

        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You are not authorized to edit this product.');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to update products.');
        }

        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You are not authorized to update this product.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'condition' => 'required|in:new,used',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to delete products.');
        }

        if ($product->user_id !== Auth::id()) {
            return redirect()->route('products.show', $product)
                ->with('error', 'You are not authorized to delete this product.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
} 