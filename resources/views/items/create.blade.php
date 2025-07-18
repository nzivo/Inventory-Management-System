@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Assets</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
            <li class="breadcrumb-item">Assets</li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">Add a New Asset</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('items.index') }}" class="btn btn-primary mt-2">View Assets</a>
                        </div>
                    </div>



                    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Image Upload Input -->
                        <div class="row mb-3">
                            <label for="item_img" class="col-sm-2 col-form-label">Asset Image</label>
                            <div class="col-sm-10">
                                <input type="file" name="item_img" id="item_img" class="form-control" accept="image/*"
                                    onchange="previewImage(event)" required>
                                <img id="imgPreview" src="" alt="Preview"
                                    style="max-width: 200px; margin-top: 10px;">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Asset Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category_id" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category_id" id="category_id" class="form-control" required>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="subcategory_id" class="col-sm-2 col-form-label">Subcategory</label>
                            <div class="col-sm-10">
                                <select name="subcategory_id" id="subcategory_id" class="form-control">
                                    <option value="">Select Subcategory</option>
                                    @foreach ($subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="brand_id" class="col-sm-2 col-form-label">Brand</label>
                            <div class="col-sm-10">
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="supplier_id" class="col-sm-2 col-form-label">Supplier</label>
                            <div class="col-sm-10">
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="branch_id" class="col-sm-2 col-form-label">Branch</label>
                            <div class="col-sm-10">
                                <select name="branch_id" id="branch_id" class="form-control" required>
                                    @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                            <div class="col-sm-10">
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                                    value="1" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="auto_generate" class="col-sm-2 col-form-label">Auto Generate Serials?</label>
                            <div class="col-sm-10">
                                <select id="auto_generate" class="form-control">
                                    <option value="no">No (Enter manually)</option>
                                    <option value="yes">Yes (Generate automatically)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3" id="serialNumbersContainer">
                            <label for="serial_number_1" class="col-sm-2 col-form-label">Serial Number #1:</label>
                            <div class="serialNumberField col-sm-10">
                                <input type="text" name="serial_numbers[]" id="serial_number_1"
                                    class="form-control mb-3" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">&nbsp;</label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn w-100 btn-primary">Add Asset</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInput = document.getElementById('quantity');
        const autoGenerateSelect = document.getElementById('auto_generate');
        const serialNumbersContainer = document.getElementById('serialNumbersContainer');

        function updateSerialNumberFields() {
            const quantity = parseInt(quantityInput.value) || 1;
            const autoGenerate = autoGenerateSelect.value === 'yes';

            serialNumbersContainer.innerHTML = ''; // Clear all current fields

            for (let i = 0; i < quantity; i++) {
                const serialValue = autoGenerate ? `ASSET-${Date.now()}-${Math.floor(Math.random() * 1000)}-${i + 1}` : '';

                const fieldHTML = `
                    <div class="row mb-3 serialNumberField">
                        <label for="serial_number_${i + 1}" class="col-sm-2 col-form-label">Serial Number #${i + 1}:</label>
                        <div class="col-sm-10">
                            <input type="text" name="serial_numbers[]" id="serial_number_${i + 1}" class="form-control"
                                ${autoGenerate ? 'readonly' : 'required'}
                                value="${serialValue}">
                        </div>
                    </div>
                `;

                serialNumbersContainer.insertAdjacentHTML('beforeend', fieldHTML);
            }
        }

        quantityInput.addEventListener('input', updateSerialNumberFields);
        autoGenerateSelect.addEventListener('change', updateSerialNumberFields);

        updateSerialNumberFields(); // Initial call
    });
</script>


<script type="text/javascript">
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Display the image preview
                document.getElementById('imgPreview').src = e.target.result;

                // Optionally store the base64 string in a hidden field
                const base64Image = e.target.result;

                // You can then add this base64 data to your form if needed
                // For now, we'll just log it in the console.
                console.log(base64Image);
            };
            reader.readAsDataURL(file);
        }
    }
</script>
