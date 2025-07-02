@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Assets</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Assets</li>
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
                            <h5 class="card-title">Add Serial Numbers for {{ $item->name }}</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('asset.assets') }}" class="btn btn-primary mt-2">View Assets</a>
                        </div>
                    </div>

                    <form action="{{ route('serialnumbers.store', $item) }}" method="POST">
                        @csrf

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
                                <button type="submit" class="btn w-100 btn-primary">Add Serial Numbers</button>
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
