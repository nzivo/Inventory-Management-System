@extends('layouts.dashboard')

@section('content')

<div class="pagetitle">
    <h1>Dispatch Request Gate Pass</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dispatch_requests.index') }}">Dispatch Requests</a></li>
            <li class="breadcrumb-item active">Gate Pass</li>
        </ol>
    </nav>
</div>
<button onclick="printSection()" class="btn btn-secondary mb-4">Print Gate Pass</button>
<br>
<section id="print_section" class="section print_section">
    <div class="row">
        <div class="col-lg-12">

            <!-- Dispatch Request Details Table -->
            <div class="card mx-auto">

                <div class="card-body mt-5">

                    <table style="width: 100%; border: none;">
                        <tr style="width: 100%;">
                            <td style="width: 100%;">
                                <h3 class="text-center"
                                    style="width: 100%; font-size: 29px; text-align:center; line-height: 1.2;">
                                    <strong>{{
                                        $company->name }}</strong>
                                </h3>

                            </td>
                        </tr>
                    </table>
                    <!-- Table Layout for Letterhead -->
                    <table style="width: 100%; border: none;">
                        <tr>
                            <!-- Column 1: Company Details -->
                            <td
                                style="width: 30%; text-align: start; vertical-align: top; font-size: 12px; line-height: 1.2;">

                                {{ $company->location }}<br>
                                {{ $company->postal_address }}, {{ $company->postal_code }}<br>
                                Phone: {{ $company->primary_phone }} @if($company->secondary_phone) | {{
                                $company->secondary_phone }} @endif<br>
                                Email: {{ $company->email }}<br>
                                Website: <a href="{{ $company->url }}" target="_blank">{{ $company->url }}</a>
                            </td>

                            <!-- Column 2: Gate Pass Badge -->
                            <td style="width: 40%; text-align: center; vertical-align: middle;">
                                <span class="badge bg-danger badge-lg display-6"
                                    style="font-size: 2.0rem; padding: 10px 20px; background-color: #DC3545; color: white; border-radius: 5px;">Gate
                                    Pass</span>
                            </td>

                            <!-- Column 3: Logo and Dispatch Request Number -->
                            <td style="width: 30%; text-align: end; vertical-align: top;">
                                <img src="{{ asset('assets/images/fon_logo.png')}}" alt="Company Logo"
                                    style="max-width: 200px; height: auto;">
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <h5 class="card-title">DISPATCH REQUEST: #{{ $dispatchRequest->dispatch_number }}</h5>
                    <hr>

                    <!-- Dispatch Request Details Table -->
                    <table class="table text-start" style="text-align: start">
                        <tr>
                            <th>User</th>
                            <td>{{ $dispatchRequest->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Site</th>
                            <td>{{ $dispatchRequest->site }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span
                                    class="badge bg-{{ $dispatchRequest->status === 'approved' ? 'success' : ($dispatchRequest->status === 'denied' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($dispatchRequest->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $dispatchRequest->description ?? 'No description available' }}</td>
                        </tr>
                        <tr>
                            <th>Request Date</th>
                            <td>{{ $dispatchRequest->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>

                    <!-- Associated Serial Numbers -->
                    <p>Items</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th> <!-- Added Number column -->
                                <th>Serial Number</th>
                                <th>Item Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dispatchRequest->serialNumbers as $index => $serial)
                            <tr>
                                <td>{{ $index + 1 }}</td> <!-- Displaying the index as a number (starting from 1) -->
                                <td>{{ $serial->serialNumber->serial_number }}</td>
                                <td>
                                    @if($serial->serialNumber->item)
                                    {{ $serial->serialNumber->item->name }}
                                    @else
                                    No item associated
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table style="width: 100%; border: none;">
                        <tr>
                            <td
                                style="width: 33%; text-align: start; vertical-align: top; font-size: 14px; line-height: 1.5;">
                                <strong>Requested By</strong></br>
                                <strong>Name:</strong> {{ $dispatchRequest->user->name }}</br>
                                <strong>Email:</strong> {{ $dispatchRequest->user->email }}</br>
                                <strong>Phone:</strong> {{ $dispatchRequest->user->phone_number ?? 'Not available' }}
                                </br>
                            </td>
                            <td
                                style="width: 33%; text-align: start; vertical-align: top; font-size: 14px; line-height: 1.5;">
                                <strong>Approved By</strong></br>
                                @if ($dispatchRequest->approver)
                                <strong>Name:</strong> {{ $dispatchRequest->approver->name }}</br>
                                <strong>Email:</strong> {{ $dispatchRequest->approver->email }}</br>
                                <strong>Phone:</strong> {{ $dispatchRequest->user->phone_number ?? 'Not available' }}
                                </br>
                                @else
                                <strong>Approver:</strong> Not yet approved</br>
                                @endif
                            </td>
                            <td
                                style="width: 33%; text-align: start; vertical-align: top; font-size: 14px; line-height: 1.5;">
                                <strong>Security Approved By</strong></br>
                                <strong>Name:</strong> </br>
                                <strong>Date:</strong> </br>
                                <strong>Signature:</strong>
                                </br>
                                <strong>Stamp:</strong>
                                </br>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #print_section,
        #print_section * {
            visibility: visible;
        }

        #print_section {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
    }
</style>
@endsection


<script type="text/javascript">
    function printSection() {
        const printContent = document.getElementById('print_section');
        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write('<html><head><title>Print {{ $dispatchRequest->dispatch_number }}</title><style>@media print { body { font-family: Arial, sans-serif; } }</style></head><body>');
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
</script>