<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
.btn-product {
    font-size: clamp(10px, 10vw, 10px);
    padding-top: 18px;
    width: 50px; /* Adjust the width as needed */
}
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <div id="content">
            <div class="container-fluid">
                @foreach ($categories as $category)
                    <hr>
                    <h5>{{ $category->name }}</h5>
                    <div class="row">
                        @foreach ($category->products as $product)
                            <div class="col-lg-4 mb-4">     
                                {{-- <form action="{{ route('getProduct') }}" method="POST">    --}}
                                    {{-- @csrf                 --}}
                                    <button class="btn btn-outline-primary btn-product" 
                                    id="productButton{{ $product->id }}"
                                    title="{{ $product->name }}" type="submit" 
                                    data-toggle="modal" data-target="#addToOrderModal" 
                                    data-product="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->price }}"
                                    name="button_id" data-product-id="{{ $product->id }}" id="buttonId">                                    
                                        <p>{{ $product->name }}</p>
                                        <p>Rp {{ $product->price }}</p>
                                    </button>   
                                {{-- </form>                                                             --}}
                            </div>                        
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <script>
        // Listen for button click event
        // document.getElementById('buttonId').addEventListener('click', function() {
        //     // Get the value from the button
        //     // var buttonValue = this.value;
        //     var buttonValue = $(this).data('product');

        //     console.log(buttonValue);

        // });

    //     var total = 0;
    // $.each(newOrderProduct, function(index, product) {
    //     total += product.productPrice * product.productQuantity;
    // });

    // // Update the total price in the HTML section
    // document.getElementById("hargabosValue").innerText = total;
    </script>
    
</body>
</html>
