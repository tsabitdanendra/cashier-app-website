@extends('layouts.app')

{{-- @section('title', 'My Page') --}}

@section('content')

<style>
    .custom-btn-width {
    width: 700px; /* Adjust the width value as per your requirements */
}
</style>

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Order List</h1>

<!-- Content Row -->
<div class="row">

    <!-- New Order -->
    <div class="col-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body d-flex flex-column align-items-center" style="height: 400px; overflow-y: auto;">
                @if (session()->has('newOrder'))                
                    <?php $orders = session('newOrder'); ?>
                    {{-- @dd($orders) --}}
                    @foreach ($orders as $order)
                    
                        <?php
                        // Calculate the total quantity for the order
                        $totalQuantity = 0;
                        foreach ($order[1] as $item) {
                            $totalQuantity += $item['productQuantity'];
                        }
                        ?>
                        <button class="btn btn-outline-primary btn-block custom-btn-width order-btn" type="button" data-toggle="modal" data-target="#orderListDetailModal" data-order-name="{{ $order[0] }}" data-order-detail="{{ json_encode($order[1]) }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="font-size: 24px;">{{ $order[0] }}</div>
                                <div>
                                    <p style="margin-bottom: 0;">Total Quantity: {{ $totalQuantity }}</p>
                                    <?php
                                    $totalPrice = 0;
                                    foreach ($order[1] as $item) {
                                        $totalPrice += ($item['productPrice'] * $item['productQuantity']);
                                    }
                                    $formattedTotalPrice = number_format($totalPrice, 0, '.', '.');
                                    ?>
                                    <p style="margin-bottom: 0;">Total Price: Rp  {{ $formattedTotalPrice }}</p>
                                </div>
                            </div>
                        </button>
                    @endforeach
                @else
                    <h6>There is no order.</h6>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- orderListDetailModal -->
<div class="modal fade" id="orderListDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderListDetailModalLongTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="orderName">
                <h5 class="modal-title" id="exampleModalLongTitle">Order Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="orderDetailContent">
                    <!-- The content of the order detail will be dynamically added here -->
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-end">
                            <button class="btn btn-danger btn-block" id="deleteOrderBtn"type="button">Delete Order</button>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="mb-0 text-primary" style="font-size: 16px; font-weight: bold;">Total Quantity: <span id="totalQuantity"></span></p>
                            <p class="mb-0 text-primary" style="font-size: 16px; font-weight: bold;">Total Price: <span id="totalPrice"></span></p>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            {{-- <button class="btn btn-secondary mx-1" type="button" id="closePrdDetailButton">Cancel</button> --}}
                            <button class="btn btn-success btn-block" type="button" id="toPaymentButton">Pay</button>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>

<!-- Are you sure? Product modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Deletion</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteProduct"data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteProduct">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Are you sure? Order modal -->
<div class="modal fade" id="deleteOrderModal" tabindex="-1" role="dialog" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteOrderModalModalLabel">Confirm Deletion</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Order?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDeleteOrder"data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteOrder">Yes</button>
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

    <!-- Your existing script imports... -->

<script>
    $(document).ready(function() {
        $('.order-btn').on('click', function() {
            var orderName = $(this).data('order-name');
            var orderDetail = $(this).data('order-detail');
            var contentHtml = '';
            var totalPrice = 0;
            var totalQuantity = 0;

            var productCount = 0;
            $('#orderName').val(orderName);

            $.each(orderDetail, function(index, item) {
                contentHtml += '<div class="card mb-2">';
                contentHtml += '<div class="card-body p-2 d-flex justify-content-between align-items-center">';
                contentHtml += '<p class="font-weight-bold text-primary m-0">' + item.productName + '</p>';
                contentHtml += '<p>Rp ' + item.productPrice + '</p>';
                contentHtml += '<p>Product Quantity: ' + item.productQuantity + '</p>';
                contentHtml += '<p>Product ID: ' + item.productId + '</p>';
                contentHtml += '<input type="hidden" id="prdOrdertDetailListIdx_' + productCount + '" value="' + (productCount) + '">';
                contentHtml += '<button class="btn btn-danger btn-circle btn-sm delete-product"><i class="fas fa-trash"></i></button>'; // Added btn-sm class for smaller button
                contentHtml += '</div>';
                contentHtml += '</div>';

                // Calculate the total quantity and total price for each item and add it to the respective variables
                productCount += 1;
                totalQuantity += parseInt(item.productQuantity);
                totalPrice += (parseInt(item.productPrice) * parseInt(item.productQuantity));

            });

            // Display the total quantity and total price in the modal footer
            $('#totalQuantity').text(totalQuantity);
            formattedTotalPrice = totalPrice.toLocaleString('en');
            $('#totalPrice').text('Rp ' + formattedTotalPrice);

            $('#orderDetailContent').html(contentHtml);
        });

        // Delete button click event handler
        $('#orderDetailContent').on('click', '.delete-product', function() {
            // Hide the orderListDetailModal when the delete-product button is clicked
            $('#orderListDetailModal').modal('hide');

            // Show the "Are you sure?" modal when the delete-product button is clicked
            $('#deleteProductModal').modal('show');

            // Save a reference to the card that needs to be deleted
            var cardToDelete = $(this).closest('.card');

            // When the "Yes" button is clicked in the "Are you sure?" modal
            $('#confirmDeleteProduct').on('click', function() {
                // Hide the "Are you sure?" modal
                $('#deleteProductModal').modal('hide');

                // Find the parent card and remove it from the DOM
                cardToDelete.remove();

                // Get the index of the deleted product
                var deletedIndex = parseInt(cardToDelete.find('input[id^="prdOrdertDetailListIdx_"]').val());
                var dltOrderName = $('#orderName').val();
                var urlDltProduct = "{{ route('dltProduct') }}";

                // Update the index of the remaining products after the deleted index
                $('#orderDetailContent').find('.card').each(function() {
                    var currentIndex = parseInt($(this).find('input[id^="prdOrdertDetailListIdx_"]').val());

                    // If the current product's index is greater than the deleted index, update its index
                    if (currentIndex > deletedIndex) {
                        var updatedIndex = currentIndex - 1;
                        $(this).find('input[id^="prdOrdertDetailListIdx_"]').val(updatedIndex);
                    }
                });

                // Perform the AJAX request to delete the product
                $.ajax({
                    url: urlDltProduct,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        dltOrderName: dltOrderName,
                        deletedIndex: deletedIndex
                    },
                    success: function(response) {
                        // Update the total quantity and total price after successful deletion
                        // updateTotalQuantAndPrice();
                        location.reload();
                        
                    },
                    error: function(xhr) {
                        console.error('Error sending array:', xhr.responseText);
                        // Handle any errors that occur during the AJAX request
                    }
                });
 

            });

            $('#cancelDeleteProduct').on('click', function() {
                // Show back the orderListDetailModal
                $('#deleteProductModal').modal('hide');
                $('#orderListDetailModal').modal('show');
            });
        });

        $('#deleteOrderBtn').on('click', function() {
            var cancelOrderName = $('#orderName').val();
            
            $('#orderListDetailModal').modal('hide');
            $('#deleteOrderModal').modal('show');  

            $('#confirmDeleteOrder').on('click', function() {
                console.log(cancelOrderName)
                var urlDltOrder = "{{ route('dltOrder') }}";

                $.ajax({
                    url: urlDltOrder,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        cancelOrderName: cancelOrderName
                    },
                    success: function(response) {
                        // Update the total quantity and total price after successful deletion                    
                        location.reload();
                        
                    },
                    error: function(xhr) {
                        console.error('Error sending array:', xhr.responseText);
                        // Handle any errors that occur during the AJAX request
                    }
                });


            });

            $('#cancelDeleteOrder').on('click', function() {
                // Show back the orderListDetailModal
                $('#deleteOrderModal').modal('hide');
                $('#orderListDetailModal').modal('show');
            });

                
        });

        $('#closePrdDetailButton').on('click', function() {            
            $('#orderListDetailModal').modal('hide');
                    
        });


        $('#toPaymentButton').on('click', function() { 
            var payOrderName = $('#orderName').val();    
            var urltoPayment = "{{ route('toPayment') }}";

            $.ajax({
                    url: urltoPayment,
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        payOrderName: payOrderName
                    },
                    success: function(response) {
                        // Update the total quantity and total price after successful deletion                    
                        console.log('nice')                                    
                        window.location.href = "{{ route('payment') }}" + "?orderToPaid=" + encodeURIComponent(JSON.stringify(response.orderToPaid));
                    },
                    error: function(xhr) {
                        console.error('Error sending array:', xhr.responseText);
                        // Handle any errors that occur during the AJAX request
                    }
                });
            
            
        });
         

        // function updateTotalQuantAndPrice() {
        //     var totalQuantity = 0;
        //     var totalPrice = 0;

        //     $('#orderDetailContent .card').each(function(index, item) {
        //         totalQuantity += parseInt($(item).find('p:contains("Product Quantity:")').text().trim().split(':')[1]);
        //         totalPrice += parseInt($(item).find('p:contains("Product Price:")').text().trim().split(' ')[1].replace(',', ''));
        //     });

        //     $('#totalQuantity').text(totalQuantity);            
        //     $('#totalPrice').text('Rp. ' + totalPrice);
        // }


        
    });

    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var input = $(this).val().toLowerCase();
            
            $(".custom-btn-width").each(function() {
                var orderName = $(this).data("order-name").toLowerCase();
                
                if (orderName.includes(input)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>


@endsection
