@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Create Brand</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Brands</li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </nav>
</div>

@if(session('success'))
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
                            <h5 class="card-title">Add New Brand</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('brands.index') }}" class="btn btn-primary mt-2">All Brands</a>
                        </div>
                    </div>

                    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Brand Name Input -->
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Brand Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="row mb-3">
                            <label for="logo" class="col-sm-2 col-form-label">Logo</label>
                            <div class="col-sm-10">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*"
                                    onchange="previewImage(event)">
                                <img id="logoPreview" src="" alt="Logo Preview"
                                    style="max-width: 200px; margin-top: 10px;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary w-100">Create Brand</button>
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
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Display the image preview
                document.getElementById('logoPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>