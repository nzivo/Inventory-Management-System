@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>All Subcategories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Subcategories</li>
            <li class="breadcrumb-item active">View</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="card-title">All Subcategories</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('subcategories.create') }}" class="btn btn-primary mt-3">Add
                                Subcategory</a>
                        </div>
                    </div>

                    <table class="table table-condensed table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subcategories as $index => $subcategory)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $subcategory->name }}</td>
                                <td>{{ $subcategory->category->name ?? 'N/A' }}</td>
                                <td>{{ $subcategory->createdBy->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('subcategories.edit', $subcategory->id) }}"
                                        class="btn btn-primary m-1">Edit</a>
                                    <button class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewSubcategory({{ $subcategory->id }}, '{{ $subcategory->name }}')">View</button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this subcategory?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="viewModalLabel">Subcategory Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Subcategory details will be populated here by AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewSubcategory(subcategoryId, subcategoryName) {
        $('#viewModalLabel').text(subcategoryName);

        $.ajax({
            url: "{{ route('subcategories.show', ':id') }}".replace(':id', subcategoryId),
            type: 'GET',
            success: function(response) {
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection