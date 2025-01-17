@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Role Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Roles</li>
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
                            <h5 class="card-title">All Roles</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            @can('role-create')
                            <a class="btn btn-success btn-sm mt-3" href="{{ route('roles.create') }}"><i
                                    class="fa fa-plus"></i> Create New Role</a>
                            @endcan
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="table table-condensed table-striped" id="datatable-two">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <!-- Show Button - Opens Modal -->
                                    <button class="btn btn-info btn-sm m-1" data-bs-toggle="modal"
                                        data-bs-target="#viewModal" onclick="viewRole({{ $role->id }})">
                                        <i class="fa-solid fa-list"></i> Show
                                    </button>

                                    @can('role-edit')
                                    <a class="btn btn-primary btn-sm m-1" href="{{ route('roles.edit', $role->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    @endcan

                                    @can('role-delete')
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                        style="display:inline"
                                        onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm m-1"><i
                                                class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $roles->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal for Role Details -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bolder" id="viewModalLabel">Role Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- Role details will be populated here by AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewRole(roleId) {
        $.ajax({
            url: "{{ route('roles.show', ':id') }}".replace(':id', roleId),
            type: 'GET',
            success: function(response) {
                // Populating the modal with role details
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection