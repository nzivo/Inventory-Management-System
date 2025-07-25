@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Create Item (Without Serials)</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('items.index') }}">Items</a></li>
            <li class="breadcrumb-item active">Create (No Serials)</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card">
        <div class="card-body pt-4">

            <form action="{{ route('item.store.basic') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">Item Name <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Description</label>
                    <div class="col-md-10">
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="category_id" class="col-md-2 col-form-label">Category <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="subcategory_id" class="col-md-2 col-form-label">Subcategory</label>
                    <div class="col-md-10">
                        <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                            <option value="">Select subcategory</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="brand_id" class="col-md-2 col-form-label">Brand</label>
                    <div class="col-md-10">
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">Select brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="supplier_id" class="col-md-2 col-form-label">Supplier</label>
                    <div class="col-md-10">
                        <select name="supplier_id" id="supplier_id" class="form-select">
                            <option value="">Select supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="branch_id" class="col-md-2 col-form-label">Branch <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select name="branch_id" id="branch_id" class="form-select" required>
                            <option value="">Select branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="quantity" class="col-md-2 col-form-label">Quantity <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="number" name="quantity" class="form-control" min="1" required value="{{ old('quantity', 1) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="item_img" class="col-md-2 col-form-label">Item Image</label>
                    <div class="col-md-10">
                        <input type="file" name="item_img" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Item
                    </button>
                </div>

            </form>
        </div>
    </div>
</section>
@endsection
