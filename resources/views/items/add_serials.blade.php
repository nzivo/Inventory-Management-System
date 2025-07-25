@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h3>Add Serial Numbers to Items</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- <form action="{{ route('item.updateSerials') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Current Serial</th>
                    <th>New Serial Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->serial_number ?? 'N/A' }}</td>
                        <td>
                            <input type="text" name="serials[{{ $item->id }}]" class="form-control">
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">All items have serial numbers</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($items->count())
            <button type="submit" class="btn btn-primary">Update Serials</button>
        @endif
    </form> --}}

    <form action="{{ route('item.updateSerials') }}" method="POST">
        @csrf
        <table class="table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Current Serial</th>
                    <th>New Serial Number</th>
                    <th>Auto-Generate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        @if($item->serial_number)
                            {{ $item->serial_number }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <input type="text" name="serials[{{ $item->id }}]" class="form-control" placeholder="Enter or leave blank">
                    </td>
                    <td>
                        <input type="checkbox" name="generate[{{ $item->id }}]" value="1">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Update Serials</button>
    </form>

</div>
@endsection

