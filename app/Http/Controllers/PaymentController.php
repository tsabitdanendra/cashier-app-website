<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{

    public function showPaymentPage(Request $request)
{
    $orderToPaid = json_decode($request->query('orderToPaid'), true);

    // Pass the $orderToPaid variable to the 'Payment' view
    return view('Payment', compact('orderToPaid'));
}


    public function paymentMethod(Request $request){

        $buttonValue = $request->input('buttonValue');
        session()->flash('successP', 'Payment Successful.');
        return redirect()->route('paymenySuccess');

    }

    public function confirmPayment(Request $request) {

        // Get the order name, total price, total quantity, product detail, user ID, and payment method from the request
        $orderName = $request->input('orderName');
        $totalPrice = $request->input('totalPrice');
        $totalQuantity = $request->input('totalQuantity');
        $productDetail = $request->input('productDetail');
        $user = session('userId');//$request->input('user');
        $paymentMthd = $request->input('paymentMthd');
        $status = "Paid";
        $orderToPaid = $request->input('orderToPaid');

        // @dd($paymentMthd);

        
    
        // dd($productDetail);
    
        // Create a new `Order` model and save it
        $order = new Order([
            'user_id' => $user,
            'orderName' => $orderName,
            'paymentMthd' => $paymentMthd,
            'totalQty' => $totalQuantity,
            'totalPrice' => $totalPrice,
            'status' => $status
        ]);

        // Store the order date in a session
        $orderDate = Carbon::now();
        Session::put('orderDate', $orderDate);
        Session::put('paymentMthd', $paymentMthd);
        
        $order->save(); // Save the order to generate the auto-incremented id

        // Access the auto-generated order id
        $orderId = $order->id;

        // Store the orderId in the session
        Session::put('orderId', $orderId);
    
        // Iterate through the `productDetail` array and create a new `OrderDetail` model for each product
        foreach (array_keys($productDetail) as $productId) {
    
            // Get the product by ID
            $product = Product::find($productId);
    
            // If the product doesn't exist, return an error
            if (!$product) {
                return response()->json(['error' => 'Invalid product ID']);
            }
            // @dd($productDetail[$productId]);
            // Create a new `OrderDetail` model
            $orderDetail = new OrderDetail([
                'order_id' => $order->id, // Use the auto-generated order id
                'product_id' => $productId,
                'productQuantity' => $productDetail[$productId]
                
            ]);
    
            // Save the `OrderDetail` model
            $orderDetail->save();
        }



        return response()->json(['orderToPaid' => $orderToPaid]);
    
        // Return a success message
               
    }
    



}


// insert data to database
//  dd(request()->all());
        // $user_id = 1;
        

        // $order = new Order([
        //     'user_id' => $user_id,
        //     'orderName' => $newOrderData[0]
        // ]);
        // // dd($order);

        // $order->save(); // Save the order to generate the auto-incremented id

        // foreach ($newOrderData[1] as $detail) {
        //     $product_id = $detail['productId'];

        //     $product = Product::find($product_id);
        //     if (!$product) {
        //         return response()->json(['error' => 'Invalid product ID']);
        //     }

        //     // dd($detail); // Add this line for debugging

        //     $order->details()->create([
        //         'order_id' => $order->id, // Use the auto-generated order id
        //         'product_id' => $product_id,
                 
        //     ]);
        // }