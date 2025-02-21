@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Manage Assets</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Assets</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">All Assets</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            @if(auth()->user()->can('create-assets'))
                            <a href="{{ route('items.create') }}" class="btn btn-primary mt-2">Add New Item</a>
                            @endif
                        </div>
                    </div>

                    <table id="datatable" class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Subcategory</th>
                                <th>Branch</th>
                                <th>Quantity</th>
                                <th>Available</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->subcategory->name ?? 'N/A' }}</td>
                                <td>{{ $item->branch->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->available_quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if(auth()->user()->can('add-asset-item'))
                                    <a href="{{ route('serialnumbers.create', $item->id) }}"
                                        class="btn btn-success btn-sm m-1">Add Item</a>
                                    @endif
                                    <a href="{{ route('asset.showAsset', $item->id) }}"
                                        class="btn btn-info btn-sm">View</a>
                                    @if(auth()->user()->can('edit-asset'))
                                    <a href="{{ route('asset.editAsset', $item->id) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    @endif
                                    @if(auth()->user()->can('delete-asset'))
                                    <button onclick="confirmDelete('{{ route('asset.destroyAsset', $item->id) }}')"
                                        class="btn btn-danger btn-sm">Delete</button>
                                    @endif
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this asset?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include DataTables script -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#itemsTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true
        });
    });
</script>
<script>
    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        $('#deleteModal').modal('show');
    }
</script>

@endsection
