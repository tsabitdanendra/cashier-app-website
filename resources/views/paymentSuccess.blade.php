<!DOCTYPE html>
<html lang="en">

<head>
    

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Cashier App</title>

    <!-- Custom fonts for this template-->
    <link href= {{ asset("assets/vendor/fontawesome-free/css/all.min.css") }} rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href={{ asset("assets/css/sb-admin-2.min.css") }} rel="stylesheet">


</head>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Successful Payment</h5>                
                        {{-- @dd($orderToPaid) --}}
                    </div>
                    <div class="card-body text-center">
                        <i class="fa fa-check-circle fa-5x text-success"></i>
                        <h4 class="mt-3"> Payment Successful!</h4>
                        <div class="mt-4">
                            <a href="{{ route('generateBill') }}" class="btn btn-primary btn-lg" target="_blank" >Print Bill</a>
                            <a href="{{ route('clearOrder') }}" class="btn btn-success btn-lg" id="backtohome">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


