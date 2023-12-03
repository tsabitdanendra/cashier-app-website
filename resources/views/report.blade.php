@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

<div class="container-fluid">

    <!-- Page Heading -->
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Report</h1>
    
        <form action="{{ route('fetchReport') }}" method="POST" class="d-flex align-items-center">
            @csrf
            <div class="input-group input-group-joined" style="width: 16.5rem;">
                <span class="input-group-text">
                    <i class="fas fa-calendar-days"></i>
                </span>
                <input class="form-control ps-0" id="startDatePicker" name="start_date" placeholder="Select start date..." />
            </div>        
            <div class="input-group input-group-joined ms-3" style="width: 16.5rem;">
                <span class="input-group-text">
                    <i class="fas fa-calendar-days"></i>
                </span>
                <input class="form-control ps-0" id="endDatePicker" name="end_date" placeholder="Select end date..." />
            </div>
    
            <button type="submit" class="btn btn-sm btn-primary shadow-sm ms-3">
                <i class="fas fa-search fa-sm text-white-50"></i> Find
            </button>
        </form>
        
        <form action="{{ route('generateReport') }}" method="GET">
            <input type="hidden" name="startdate" id="startdate" value ="{{ $startDate }}">        
            <input type="hidden" name="enddate" id="enddate" value ="{{ $endDate }}">
            <button type="submit" name="generateReport" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
            </button> 
        </form>
    </div>

    <div>
        <h6 class="text-primary font-weight-bold"> Period: ({{ $startDate }}) - ({{ $endDate }}) </h6>
    </div>
    
        
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPrice }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pembelian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTrans }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Produk
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $totalProd }}</div>
                                </div>                            
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mug-hot fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Modal</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Produk</h6>
                </div>
                <div class="card-body">                                            
                    @if (!empty($topOrderedProductsInfo))   
                        @foreach($topOrderedProductsInfo as $index => $item)
                        <div class="card mb-2">                                                                                            
                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold text-primary m-0">{{ $index + 1 }}</h5> 
                                <p class="font-weight-bold text-success m-0"> {{ $item['name'] }}</p>
                                <p class="font-weight-bold text-primary m-0">Quantity: {{ $item['totalQuantity'] }}</p>                                    
                            </div>
                        </div>
                            @endforeach
                    @else
                        <h6>No Data</h6>
                    @endif                                                                
                </div>
            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Metode Pembayaran</h6>
                </div>
                <div class="card-body">   
                    {{-- @dd($mostUsedPaymentMethod)                                          --}}
                    @if (!empty($mostUsedPaymentMethod))   
                    
                        @foreach($mostUsedPaymentMethod as $index => $item)
                        <div class="card mb-2">
                                                                                            
                            <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold text-primary m-0">{{ $index + 1 }}</h5> 
                                <p class="font-weight-bold text-success m-0"> {{ $item['method'] }}</p>
                                <p class="font-weight-bold text-primary m-0">Quantity: {{ $item['quantity'] }}</p>                                    
                            </div>
                        </div>
                         @endforeach
                    @else
                        <h6>No Data</h6>
                    @endif                     
                </div>
                            
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const startPicker = new Litepicker({ 
        element: document.getElementById('startDatePicker') 
    });
    
    const endPicker = new Litepicker({ 
        element: document.getElementById('endDatePicker') 
    });

    // const generateReportButton = document.querySelector('.btn-primary#findReport');

    // generateReportButton.addEventListener('click', function() {
    //     const startDateLitepickerObj = startPicker.getDate();
    //     const endDateLitepickerObj = endPicker.getDate();

    //     if (startDateLitepickerObj && endDateLitepickerObj) {
    //         const startDate = startDateLitepickerObj.dateInstance; // Extract dateInstance
    //         const endDate = endDateLitepickerObj.dateInstance;     // Extract dateInstance
            
    //         console.log(startDate); // Regular JavaScript Date object
    //         console.log(endDate);   // Regular JavaScript Date object
            
    //         const payload = {
    //             start: startDate.toISOString(), // Convert to ISO 8601 string
    //             end: endDate.toISOString()      // Convert to ISO 8601 string
    //         };

    //         // Now you can send the payload to the controller
    //         // sendDataToController(payload);
    //         console.log(payload);
    //     } else {
    //         console.log('Please select both start and end dates.');
    //     }
    // });

   
});


</script>

@endsection