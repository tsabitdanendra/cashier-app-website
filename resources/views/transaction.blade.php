@extends('layouts.app')

@section('title', 'My Page')

@section('content')

        
     <!-- Page Heading -->
     <h1 class="h3 mb-2 text-gray-800">Transaction</h1>
     {{-- <p class="mb-4">Chart.js is a third party plugin that is used to generate the charts in this theme.
         The charts below have been customized - for further customization options, please visit the <a
             target="_blank" href="https://www.chartjs.org/docs/latest/">official Chart.js
             documentation</a>.</p> --}}

     <!-- Content Row -->
     <div class="row">
        {{-- @dd(session('name')); --}}
         <div class="col-xl-8 col-lg-7">

             <!-- Menu -->
             <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Menu</h6>
                        {{-- <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Category
                            </button>
                            <div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Coffee</a>
                                <a class="dropdown-item" href="#">Non-Coffee</a>
                                <a class="dropdown-item" href="#">Food</a>
                                <a class="dropdown-item" href="#">Others</a>
                            </div>
                        </div>     --}}
                    </div>
                </div>
                <div class="card-body" style="height: 420px; overflow-y: auto;">
                    {{-- <div class="wrapper" style="width: 10px;"> --}}
                        @include('menu', ['categories' => $categories])
                    {{-- </div> --}}
                </div>
            </div>
            
         </div>

         <!-- New Order -->
         <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">New Order</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="height: 250px; overflow-y: auto;">              
                    <div class="wrapper" id ="newOrder">
                        <input type="hidden" id="total" name="total" value="0">
                    </div>                                                        
                </div>
                <div class="card-footer">
                    <div class="total-price" >
                        <h5 class="text-success"><strong id="hargabos">Rp <span id="hargabosValue">0</span></strong></h5>

                    </div>
                    {{-- <hr> --}}
                    <div class="text-center mt-4">
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button class="btn btn-success btn-block" type="button" data-toggle="modal" data-target="#addToOrderListModal" id="appendNewOrderbtn"disabled>New</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="modal" data-target="#payModal"id="toPaymentBtn" disabled>Pay</button>                            
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex flex-column align-items-center">
                            <div class="col-12">
                                <button class="btn btn-outline-success btn-block" id="addProductToExistOrderButton" type="button" data-toggle="modal" data-target="#existOrderModal" disabled>Add</button>
                            </div>                        
                        </div>
                    </div>                    
                </div>
            </div>
        </div>        
     </div>

     <!-- Button trigger modal -->

<!-- Pay modal -->
{{-- <form method="POST" action="{{ route('payButton') }}"> --}}
    @csrf
    <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Continue to Payment?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="false">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" id="orderNamePay" name="orderNamePay" class="form-control" placeholder="Order Name" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="payButton">Pay</button>
                </div>
            </div>
        </div>
    </div>
{{-- </form> --}}

<!-- addToOrderList modal -->
{{-- <form method="POST" action="{{ route('addToOrderList') }}"> --}}
    @csrf
    <div class="modal fade" id="addToOrderListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add to Order List</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="false">X</span>
                    </button>
                </div>
                <div class="modal-body">                                    
                    <input type="text" id="orderNameAdd" name="orderNameAdd" class="form-control" placeholder="Order Name" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit" id="addButton">Add</button>
                </div>
            </div>
        </div>
    </div>
{{-- </form> --}}
{{-- existOrderModal --}}
<div class="modal fade" id="existOrderModal" tabindex="-1" role="dialog" aria-labelledby="existOrderModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add to Order List</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="false">X</span>
                </button>
            </div>
            <div class="modal-body">
                @if (session()->has('newOrder'))
                {{-- @dd(session('newOrder')) --}}
                {{-- @dd(session('newOrder')[0][1][0]['productPrice']) --}}
                    <?php $nameIndex = count(session('newOrder')) ?>
                    @for($i = 0; $i < $nameIndex; $i++)
                        {{-- <div class="card mb-3 border-left-0 border-right-0"> --}}
                            <button class="btn btn-outline-primary btn-block order-button" type="button" data-toggle="modal" data-target="#orderListDetailModal" data-order-name="{{ session('newOrder')[$i][0] }}">
                                <div class="d-flex align-items-center justify-content-center" style="height: 100%;">
                                    <div style="font-size: 24px;">{{ session('newOrder')[$i][0] }}</div>
                                </div>
                            </button>
                        {{-- </div> --}}
                    @endfor
                @else
                    <h6>There is no order.</h6>
                @endif
            </div>
            <div class="modal-footer">
                <input type="text" id="existOrderName" name="existOrderName" class="form-control text-center text-primary font-weight-bold" style="font-size: 20px;" placeholder="" readonly>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" id="addExistButton" disabled>Add</button>
            </div>
        </div>
    </div>
</div>

<!-- new order Modal -->
<div class="modal fade" id="addToOrderModal" tabindex="-1" role="dialog" aria-labelledby="addToOrderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToOrderModalLabel">Add to New Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <form method="POST" action="{{ route('order.addToNewOrder') }}"> --}}
                {{-- @csrf --}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productName">Product Name:</label>
                        <input type="text" class="form-control" id="productName" name="productName"
                             readonly>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">Product Price:</label>
                        <input type="text" class="form-control" id="productPrice" name="productPrice"
                             readonly>
                    </div>
                    <div class="form-group">
                        <label for="productQuantity">Product Quantity:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                            </div>
                            <input type="text" class="form-control text-center" id="productQuantity"
                                name="productQuantity" value="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="modalSubmitButton">Add</button>
                </div>
            {{-- </form> --}}
        </div>
    </div>
</div>

<style>
    .btn-product {
            width: 100%;
            /* font-size: clamp(10px, 4vw, 12px); */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            /* white-space: nowrap; */
            /* overflow: hidden; */
            /* text-overflow: ellipsis; */
            font-weight: bold;
        }

    .product {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .delete-button {
        background-color: red;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }
    .delete-button:hover {
        background-color: darkred;
    }
</style>

<script>

    
    $('#addExistButton').click(function() {
        var modal = $('#existOrderModal'); 
        var orderName = document.getElementById("existOrderName").value;
        var newProduct = newOrderProduct;
    
        console.log(orderName)
        console.log(newProduct)


        var urlAddToSession = "{{ route('addToSession') }}";

        $.ajax({
        url: urlAddToSession,  // Replace with the actual route to your Laravel controller
        type: 'POST',  // Use the appropriate HTTP method (POST, GET, etc.)
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            orderName: orderName,
            newProduct: newProduct
        },
        success: function(response) {
            console.log('Array sent successfully!');
            modal.modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            location.reload();
            
        },
        error: function(xhr) {
            console.error('Error sending array:', xhr.responseText);
            // Handle any errors that occur during the AJAX request
        }
        });

        

    });
    
    
    var newOrderProduct = [];
    var newOrderProductLength = newOrderProduct.length
    var newOrderName;
    var newOrder = [];

    $('#addButton').on('click', function () {
        var modal = $('#addToOrderListModal'); 
        var orderNameInput = document.getElementById("orderNameAdd");
        newOrderName = orderNameInput.value; 

        newOrder = [newOrderName, newOrderProduct];

        modal.modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        console.log(newOrder);

        var urlNewOrder = "{{ route('addNewOrder') }}";

        // Send the array to the server using AJAX
        $.ajax({
        url: urlNewOrder,  // Replace with the actual route to your Laravel controller
        type: 'POST',  // Use the appropriate HTTP method (POST, GET, etc.)
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            newOrder: newOrder
        },
        success: function(response) {
            console.log('Array sent successfully!');
            location.reload()
            // console.log(newOrder[1][0].productName);
            // Handle the response from the server if needed
        },
        error: function(xhr) {
            console.error('Error sending array:', xhr.responseText);
            // Handle any errors that occur during the AJAX request
        }
        });


    });

    $('#payButton').on('click', function () {
        var modal = $('#payModal'); 
        var orderNameInput = document.getElementById("orderNamePay");
        newOrderName = orderNameInput.value;      

        newOrder = [newOrderName, newOrderProduct];

        modal.modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        console.log(newOrder);

        var urldirectToPayment = "{{ route('directToPayment') }}";

        // Send the array to the server using AJAX
        $.ajax({
        url: urldirectToPayment,  // Replace with the actual route to your Laravel controller
        type: 'POST',  // Use the appropriate HTTP method (POST, GET, etc.)
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            newOrder: newOrder,
            newOrderName: newOrderName,
            // orderNameInput: orderNameInput,
        },
        success: function(response) {
            console.log('Array sent successfully!');
            // console.log(response.orderToPaid)
            window.location.href = "{{ route('payment') }}" + "?orderToPaid=" + encodeURIComponent(JSON.stringify(response.orderToPaid));
            // console.log(newOrder[1][0].productName);
            // Handle the response from the server if needed
        },
        error: function(xhr) {
            console.error('Error sending array:', xhr.responseText);
            // Handle any errors that occur during the AJAX request
        }
        });


    });


    $(document).ready(function () {
    

        
    // Variables
    var modal = $('#addToOrderModal');
    var quantityInput = modal.find('#productQuantity');
    var priceInput = modal.find('#productPrice');
    var productName = null;
    var productPrice = null;
    var productId = null;
    

    // Event listener for modal show event
    modal.on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        productId = button.data('product-id');
        productName = button.data('product-name');
        productPrice = button.data('product-price');
        
        

        // Update the input fields in the modal with the product name and price
        modal.find('#productName').val(productName);
        priceInput.val("Rp " + productPrice);

        // modal.find('#productQuantity').val(1);

        // Handle quantity increment and decrement
        modal.find('#increaseQuantity').on('click', function () {
            incrementQuantity();
        });

        modal.find('#decreaseQuantity').on('click', function () {
            decrementQuantity();
        });

    });

    // Handle "Add to Order" button click
    $('#modalSubmitButton').on('click', function () {
        var quantity = quantityInput.val();
        if (productName && productPrice && quantity) {
            addToOrder(productName, productPrice, quantity, productId);
            
        } else {
            console.log('Invalid input');
        }
    });

    // Function to increment the quantity
    function incrementQuantity() {
        var quantity = parseInt(quantityInput.val());
        quantityInput.val(quantity + 1);
    }

    // Function to decrement the quantity
    function decrementQuantity() {
        var quantity = parseInt(quantityInput.val());
        if (quantity > 1) {
            quantityInput.val(quantity - 1);
        }
    }

    // Enhancements for quantity input usability
    quantityInput.on('input', function () {
        var quantity = parseInt(quantityInput.val());
        if (isNaN(quantity) || quantity < 1) {
            quantityInput.val(1);
        }
    });

    // Function to add the product to the order
    function addToOrder(productName, productPrice, productQuantity, productId) {
        var url = "{{ route('submitButton') }}";

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                productName: productName,
                productPrice: productPrice,
                productQuantity: productQuantity,
                productId: productId
            },
            success: function (response) {
                appendProducts(response);
                modal.modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();

                //get newOrder
                newOrderProduct.push(response.productDetails);
                // console.log(response)
                console.log(newOrderProduct)
                // console.log(newOrderProduct)
                var pricetotal = 0;
                $.each(newOrderProduct, function(index, product) {
                    // console.log(product.productPrice)
                    pricetotal += product.productPrice * product.productQuantity;
                });

                // Update the total price in the HTML section
                formattedPriceTotal = pricetotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                document.getElementById("hargabosValue").innerText = formattedPriceTotal;
            },
            error: function (xhr) {
                console.log(xhr.responseText);
            }
        });
    }


    // Function to append the products to the order list
    function appendProducts(products) {

        var totalInput = document.getElementById("total").value;
        // console.log(totalInput)
        
        
        var baru =$('#newOrder').children().length;
        

        $.each(products, function(key, value) {
            var productHTML = '<div class="card mb-3">';
            productHTML += '<div class="card-body p-2 d-flex justify-content-between align-items-center">';
            productHTML += '<div>';
            productHTML += '<h5 class="card-title mb-1">' + value.productName + '</h5>';
            productHTML += '<p class="card-text small mb-1">' + value.productPrice + ' x ' + value.productQuantity + '</p>';
            productHTML += '<p class="card-text small mb-0">Price: ' + (value.productQuantity * value.productPrice) + '</p>';
            productHTML += '<input type="hidden" id="productId" value="' + productId + '">';
            productHTML += '<input type="hidden" id="newOrderListIdx_' + baru + '" value="' + (baru - 1) + '">';
            productHTML += '<input type="hidden" id="harga_' + baru + '" value="' + (value.productQuantity * value.productPrice) + '">';
            productHTML += '</div>';
            productHTML += '<button class="btn btn-danger btn-circle btn-sm delete-product"><i class="fas fa-trash"></i></button>'; // Added btn-sm class for smaller button
            productHTML += '</div>';
            productHTML += '</div>';

        

             $('#newOrder').append(productHTML);
        });

        document.getElementById("total").value = baru;

        var addProductToExistOrderButton = $('#addProductToExistOrderButton');
        var toPaymentBtn = $('#toPaymentBtn');
        var appendNewOrderbtn = $('#appendNewOrderbtn');

        if ($('#newOrder').children().length > 0) {
            addProductToExistOrderButton.prop('disabled', false);
            toPaymentBtn.prop('disabled', false);
            appendNewOrderbtn.prop('disabled', false);
        } else {
            addProductToExistOrderButton.prop('disabled', true);
            toPaymentBtn.prop('disabled', true);
            appendNewOrderbtn.prop('disabled', true);
        }

        // get index
        // var newOrderListIdx_ = parseInt(document.getElementById("newOrderListIdx_" + baru).value);
        
        // newOrder.push(newOrderListIdx_);
        // console.log(newOrderListIdx_);

    }


    
    // Delete button click event handler
    $('#newOrder').on('click', '.delete-product', function() {
    // Find the parent card and remove it from the DOM
    var card = $(this).closest('.card');
    card.remove();

    // Get the index of the deleted product
    var deletedIndex = parseInt(card.find('input[id^="newOrderListIdx_"]').val());

    $('#newOrder').find('.card').each(function() {
        var currentIndex = parseInt($(this).find('input[id^="newOrderListIdx_"]').val());

        // If the current product's index is greater than the deleted index, update its index
        if (currentIndex > deletedIndex) {
            var updatedIndex = currentIndex - 1;
            $(this).find('input[id^="newOrderListIdx_"]').val(updatedIndex);
            $(this).find('input[id^="harga_"]').attr('id', 'harga_' + updatedIndex); // Update the ID of the "harga_" input
        }
    });

    newOrderProduct.splice(deletedIndex, 1);
    
    console.log(newOrderProduct)

    // Recalculate total price
    var pricetotal = 0;
    $.each(newOrderProduct, function(index, product) {
        pricetotal += product.productPrice * product.productQuantity;
    });

    // Update the total price in the HTML section
    formattedPriceTotal = pricetotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    document.getElementById("hargabosValue").innerText = formattedPriceTotal;

        var addProductToExistOrderButton = $('#addProductToExistOrderButton');
        var toPaymentBtn = $('#toPaymentBtn');
        var appendNewOrderbtn = $('#appendNewOrderbtn');

        if ($('#newOrder').children().length > 0) {
            addProductToExistOrderButton.prop('disabled', false);
            toPaymentBtn.prop('disabled', false);
            appendNewOrderbtn.prop('disabled', false);
        } else {
            addProductToExistOrderButton.prop('disabled', true);
            toPaymentBtn.prop('disabled', true);
            appendNewOrderbtn.prop('disabled', true);
        }

    });

    $('.order-button').click(function() {
            var orderName = $(this).data('order-name');
            $('#existOrderName').val(orderName);

            var addExistButton = $('#addExistButton');

            if (orderName !== '') {
                addExistButton.prop('disabled', false);
                } else {
                addExistButton.prop('disabled', true);
            }
        });        
    

});


$(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            var input = $(this).val().toLowerCase();
            
            $(".btn-product").each(function() {
                var productName = $(this).data("product-name").toLowerCase();
                
                if (productName.includes(input)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });


</script>







{{-- <script>
    $('#addToOrderModal').on('#modalSubmitButton', function (event) {
        // Event handler function for the "modalSubmitButton" event
        
        // Prevent the default form submission behavior
        event.preventDefault();
        
        // Access the form fields and their values
        // var form = $(this).find('form');
        var productName = button.data('product-name');
        
        
        // Create an object with the form data
        var formData = {
            productName: productName,
            
        };
        
        // Perform an AJAX request to submit the form data to a server-side endpoint
      
    });
</script> --}}




@endsection