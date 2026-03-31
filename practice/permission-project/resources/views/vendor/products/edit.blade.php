@extends('layouts.app')
@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="page-header">
    <div><h1>Edit: {{ $product->name }}</h1></div>
    <a href="{{ route('vendor.products') }}" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Back</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil me-2"></i>Edit Details</div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('vendor.products.update', $product) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $product->name) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price (PKR) *</label>
                            <input type="number" name="price" class="form-control" step="0.01" min="0"
                                   value="{{ old('price', $product->price) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock *</label>
                            <input type="number" name="stock" class="form-control" min="0"
                                   value="{{ old('stock', $product->stock) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $product->category) }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-600" for="is_active">Active / Listed</label>
                            </div>
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>Update Product
                            </button>
                            <a href="{{ route('vendor.products') }}" class="btn btn-light ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
