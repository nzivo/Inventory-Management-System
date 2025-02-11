@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Users Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Users</li>
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
                            <h5 class="card-title">All Users</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('users.create') }}" class="btn btn-success mt-3">Create New User</a>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="table table-condensed table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                    <label class="badge bg-primary">{{ $role->name }}</label>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->status == 'active')
                                    <span class="badge bg-success">{{ $user->status }}</span>
                                    @elseif($user->status == 'inactive')
                                    <span class="badge bg-danger">{{ $user->status }}</span>
                                    @elseif($user->status == 'pending')
                                    <span class="badge bg-warning">{{ $user->status }}</span>
                                    @else
                                    <span class="badge bg-secondary">{{ $user->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Show Button - Opens Modal -->
                                    <button class="btn btn-info btn-sm m-1" data-bs-toggle="modal"
                                        data-bs-target="#viewModal" onclick="viewUser({{ $user->id }})">
                                        <i class="fa-solid fa-list"></i> Show
                                    </button>
                                    <a class="btn btn-primary btn-sm m-1" href="{{ route('users.edit',$user->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm m-1"><i
                                                class="fa-solid fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {!! $data->links('pagination::bootstrap-5') !!}
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
                <h5 class="modal-title fw-bolder" id="viewModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="viewModalBody">
                <!-- User details will be populated here by AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function viewUser(userId) {
        $.ajax({
            url: "{{ route('users.show', ':id') }}".replace(':id', userId),
            type: 'GET',
            success: function(response) {
                // Populating the modal with user details
                $('#viewModalBody').html(response);
            }
        });
    }
</script>

@endsection