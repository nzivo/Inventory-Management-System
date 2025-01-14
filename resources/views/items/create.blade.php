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
                                <img id="imgPreview" src="" alt="Image Preview"
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
                            <label for="type" class="col-sm-2 col-form-label">Item Type</label>
                            <div class="col-sm-10">
                                <input type="text" name="type" id="type" class="form-control" required>
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
    document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const serialNumbersContainer = document.getElementById('serialNumbersContainer');

    function updateSerialNumberFields() {
    const quantity = parseInt(quantityInput.value);
    const currentFields = serialNumbersContainer.querySelectorAll('.serialNumberField');

    // Remove excess fields
    if (currentFields.length > quantity) {
    for (let i = currentFields.length - 1; i >= quantity; i--) {
    currentFields[i].remove();
    }
    }

    // Add missing fields
    for (let i = currentFields.length; i < quantity; i++) { const serialNumberField=document.createElement('div');
        serialNumberField.classList.add('serialNumberField'); serialNumberField.innerHTML=`<div class="row mb-3"> <label
        for="serial_number_${i + 1}" class="col-sm-2 col-form-label">Serial Number #${i + 1}:</label>
        <div class="col-sm-10">
        <input type="text" name="serial_numbers[]" id="serial_number_${i + 1}" class="form-control" required></div></div>
        `;
        serialNumbersContainer.appendChild(serialNumberField);
        }
        }

        // Initial population of serial number fields
        updateSerialNumberFields();

        // Add event listener to quantity input
        quantityInput.addEventListener('input', updateSerialNumberFields);
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