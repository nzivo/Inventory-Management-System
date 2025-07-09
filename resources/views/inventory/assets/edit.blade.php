@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Asset</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('asset.updateAsset', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $item->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" name="description">{{ $item->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" value="{{ $item->quantity }}" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" name="status">
                        <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-control" name="category_id" required>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $item->category_id ? 'selected' : '' }}>{{
                            $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subcategory_id" class="form-label">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="form-control">
                        <option value="">Select Subcategory</option>
                        @foreach ($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ $subcategory->id == $item->subcategory_id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-control" name="branch_id" required>
                        @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == $item->branch_id ? 'selected' : '' }}>{{ $branch->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="inventory_status" class="form-label">Inventory Status</label>
                    <select class="form-control" name="inventory_status" required>
                        <option value="in_stock" {{ $item->inventory_status == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ $item->inventory_status == 'out_of_stock' ? 'selected' : '' }}>Out of Stock
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('asset.assets') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const categorySelect = document.getElementById("category_id");
        const subcategorySelect = document.getElementById("subcategory_id");

        categorySelect.addEventListener("change", function () {
            const categoryId = this.value;

            if (categoryId) {
                fetch(`/get-subcategories/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
                        data.forEach(subcategory => {
                            const option = document.createElement("option");
                            option.value = subcategory.id;
                            option.text = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                subcategorySelect.innerHTML = '<option value="">Select Subcategory</option>';
            }
        });
    });
</script>

