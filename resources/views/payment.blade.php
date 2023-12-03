@extends('layouts.app')

@section('title', 'My Page')

@section('content')
     <!-- Page Heading -->
     {{-- @dd(session('name')); --}}
     <h1 class="h3 mb-2 text-gray-800">Payment</h1>

     {{-- <p class="mb-4">Chart.js is a third party plugin that is used to generate the charts in this theme.
         The charts below have been customized - for further customization options, please visit the <a
             target="_blank" href="https://www.chartjs.org/docs/latest/">official Chart.js
             documentation</a>.</p> --}}

     <!-- Content Row -->
     <div class="row">

         <div class="col-xl-8 col-lg-7">

             <!-- Order Detail -->
             <div class="card shadow mb-1">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Order Detail</h6>
                    </div>
                </div>
                <div class="card-body p-2" style="height: 400px; overflow-y: auto;">
                    <div class="container">
                        @foreach($orderToPaid[1] as $index => $item)
                            <div class="card mb-2">
                                <div class="p-2 d-flex justify-content-between align-items-center">
                                    <h5 class="font-weight-bold text-primary m-0">{{ $item['productName'] }}</h5> 
                                </div>
                                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                    <p class="font-weight-bold text-success m-0">Rp. {{ $item['productPrice'] }}</p>
                                    <p class="font-weight-bold text-primary m-0">Product Quantity: {{ $item['productQuantity'] }}</p>
                                    <p class="m-0">Product ID: {{ $item['productId'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>                                                
            
         </div>

         <!-- Order Summary -->
         <div class="col-xl-4 col-lg-5">
            {{-- @dd(session('name')); --}}
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="height: 140px; overflow-y: auto;">                                        
                    <div class="container align-items-center">
                        {{-- <hr> --}}
                    
                        <h2 class="m-0 font-weight-bold text-primary align-items-center text-center" id="orderName">{{ $orderToPaid[0] }}</h2>                         
                    
                    @php                        
                        $totalQuantity = 0;
                        $totalPrice = 0;
                    @endphp

                    @foreach ($orderToPaid[1] as $item)
                        @php
                            $totalQuantity += $item['productQuantity'];
                            $totalPrice += ($item['productQuantity'] * $item['productPrice']);
                            $formattedTotalPrice = number_format($totalPrice, 0, '.', '.');
                        @endphp
                    @endforeach
                    <hr>
                    <h6 class="m-0 font-weight-bold text-primary align-items-center text-center" id="totalQty">Total Quantity: {{ $totalQuantity }}</h6>                
                                               
                    </div>                                  
                </div>
                <div class="card-footer">
                    <div class="total-price" >
                        <h1 class="m-0 font-weight-bold text-success text-center">Rp <span id="hargabosValue"> {{ $formattedTotalPrice }} </span></h1>

                    </div>
                    {{-- <hr> --}}
                    <div class="text-center mt-4">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <a class="btn btn-danger btn-block" href="{{ route('orderList') }}">Cancel</a>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="modal" data-target="#paymentMethodModal">Pay</button>
                            </div>
                        </div>
                    </div>                  
                </div>
            </div>
        </div>        
     </div>

     
        @csrf
        <div class="modal fade" id="paymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Select Payment Method</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="false">X</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body d-flex justify-content-center">
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="cash">Cash</button>
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="debit">Debit</button>
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="qris">Qris</button>
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="gopay">Gopay</button>
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="ovo">Ovo</button>
                            <button class="btn btn-outline-primary mx-2 payment-method" type="button" data-method="dana">Dana</button>
                        </div>
                        
                    </div>                  
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" id="confirmPayment" disabled>Pay</button> <!-- Add the 'disabled' attribute -->
                    </div>
                </div>
            </div>
        </div>


    <script src={{ asset("assets/vendor/jquery/jquery.min.js") }}></script>
    <script src={{ asset("assets/vendor/bootstrap/js/bootstrap.bundle.min.js") }}></script>
    
    <!-- Core plugin JavaScript-->
    <script src={{ asset("assets/vendor/jquery-easing/jquery.easing.min.js") }}></script>

    <!-- Custom scripts for all pages-->
    <script src={{ asset("assets/js/sb-admin-2.min.js") }}></script>

    <script>
        $('#confirmPayment').on('click', function() {
            const selectedMethod = $('.payment-method.active').data('method');   
            // var user = 1;         
            var orderName = "{{ $orderToPaid[0] }}";
            var totalPrice = {{ $totalPrice }};
            var productDetail = @json(array_column($orderToPaid[1], 'productQuantity', 'productId'));
            var totalQuantity = {{ $totalQuantity }};   
            var paymentMthd = $('.payment-method.active').data("method") 
            var orderToPaid = {{ Js::from($orderToPaid) }}            
            
            console.log(productDetail)

            

                                     

            var url = "{{ route('confirmPayment') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    orderName: orderName,
                    totalPrice: totalPrice,
                    totalQuantity: totalQuantity,
                    productDetail: productDetail,
                    paymentMthd: paymentMthd,
                    // user: user,
                    orderToPaid: orderToPaid,
                    
                },
                success: function (response) {
                    console.log(response.orderToPaid)
                    window.location.href = "{{ route('paymentSuccess') }}" + "?orderToPaid="  + encodeURIComponent(JSON.stringify(response.orderToPaid));
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
            

            
            
            // You can do further processing or send the selectedMethod value to the server here
        });


        $('.payment-method').on('click', function() {
            // Remove the 'active' class from all buttons
            $('.payment-method').removeClass('active');

            // Add the 'active' class to the clicked button
            $(this).addClass('active');

            // Check if any payment method button is active
            const isPaymentMethodSelected = $('.payment-method.active').length > 0;

            // Enable or disable the "Pay" button based on the selection
            $('#confirmPayment').prop('disabled', !isPaymentMethodSelected);
        });
    </script>
    
    
@endsection