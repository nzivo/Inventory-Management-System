@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>All Categories</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Categories</li>
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
                            <h5 class="card-title">All Categories</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">Add Category</a>
                        </div>
                    </div>

                    <table class="table table-condensed table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->creator->name ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}"
                                        class="btn btn-primary m-1">Edit</a>
                                    <button class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#viewModal"
                                        onclick="viewCategory({{ $category->id }}, '{{ $category->name }}')">View</button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                <h5 class="modal-title fw-bolder" id="viewModalLabel">Category Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Category details will be populated here by AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewCategory(categoryId, categoryName) {
        $('#viewModalLabel').text(categoryName);

        $.ajax({
            url: "{{ route('categories.show', ':id') }}".replace(':id', categoryId),
            type: 'GET',
            success: function(response) {
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection