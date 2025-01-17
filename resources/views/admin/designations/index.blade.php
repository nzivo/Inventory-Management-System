@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Manage Designations</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item active">Designations</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-10">
                            <h5 class="card-title">All Designations</h5>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('designations.create') }}" class="btn btn-primary mt-2">Add
                                Designation</a>
                        </div>
                    </div>
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Designation Name</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($designations as $key => $designation)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $designation->designation_name }}</td>
                                <td>{{ $designation->department_name }}</td>
                                <td>
                                    <a href="{{ route('designations.edit', $designation->id) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('designations.destroy', $designation->id) }}" method="POST"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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

@endsection