@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Product</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Books" {{ old('category', $product->category) == 'Books' ? 'selected' : '' }}>Books</option>
                                <option value="Electronics" {{ old('category', $product->category) == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                <option value="Fashion" {{ old('category', $product->category) == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                <option value="Food" {{ old('category', $product->category) == 'Food' ? 'selected' : '' }}>Food</option>
                                <option value="Others" {{ old('category', $product->category) == 'Others' ? 'selected' : '' }}>Others</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="condition" class="form-label">Condition</label>
                            <select class="form-select @error('condition') is-invalid @enderror" 
                                id="condition" name="condition" required>
                                <option value="">Select Condition</option>
                                <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>New</option>
                                <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>Used</option>
                            </select>
                            @error('condition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            @if($product->image)
                                <div class="mb-2">
                                    <label class="form-label">Current Image</label>
                                    <img src="{{ Storage::url($product->image) }}" alt="Product Image" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                            <label for="image" class="form-label">New Image (optional)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 