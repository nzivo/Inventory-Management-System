@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Permission Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Permissions</li>
            <li class="breadcrumb-item active">Manage</li>
        </ol>
    </nav>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">All Permissions</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('permissions.create') }}" class="btn btn-success mt-2">Add
                                Permission</a>
                        </div>
                    </div>
                    @can('permission-list')
                    <table class="table table-condensed table-striped" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>

                                    <!-- Edit Button -->
                                    @can('permission-edit')
                                    <!-- Ensure you have the permission to edit -->
                                    <a class="btn btn-primary btn-sm m-1"
                                        href="{{ route('permissions.edit', $permission->id) }}">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    @endcan

                                    <!-- Delete Button -->
                                    @can('permission-delete')
                                    <!-- Ensure you have the permission to delete -->
                                    <form method="POST" action="{{ route('permissions.destroy', $permission->id) }}"
                                        style="display:inline"
                                        onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm m-1">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endcan

                    {!! $permissions->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection