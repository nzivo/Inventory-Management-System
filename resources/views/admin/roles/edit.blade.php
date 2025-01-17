@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Edit Role</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item">Roles</li>
            <li class="breadcrumb-item active">Edit</li>
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
                            <h5 class="card-title">Edit Role</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('roles.index') }}" class="btn btn-primary mt-2">View Roles</a>
                        </div>
                    </div>

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Role Name</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}"
                                    placeholder="Enter role name" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Permissions</label>
                            <div class="col-sm-10">
                                @foreach($permission as $value)
                                <div class="form-check">
                                    <input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}"
                                        class="form-check-input" id="permission{{$value->id}}" {{ in_array($value->id,
                                    $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission{{$value->id}}">{{ $value->name
                                        }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn w-100 btn-primary">Update Role</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection