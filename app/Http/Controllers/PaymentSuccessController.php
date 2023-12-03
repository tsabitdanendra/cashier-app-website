<?php

namespace App\Http\Controllers;
use PDF;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Http\Request;

class PaymentSuccessController extends Controller
{
    public function showPaymentSuccessPage(Request $request){

        $orderToPaid = json_decode($request->query('orderToPaid'), true);
        

        // @dd($orderToPaid);
        session(['orderToPaid' => $orderToPaid]);

    
        return view('paymentSuccess', compact('orderToPaid'));
        
    }

    public function generateBill()
    {
        $order = session('orderToPaid');
        $orderDate = session('orderDate');
        $orderId = session('orderId');
        $name = session('name');
        $paymentMthd = session('paymentMthd');

        // @dd($paymentMthd);
        
        $orderName = $order[0];
        $orderItems = $order[1];
        
        $currentDateTime = date('Y-m-d H:i:s'); // Get the current datetime
        
        $totalPrice = array_reduce($orderItems, function ($carry, $item) {
            return $carry + ($item['productPrice'] * $item['productQuantity']);
        }, 0);
        
        $totalQuantity = array_reduce($orderItems, function ($carry, $item) {
            return $carry + $item['productQuantity'];
        }, 0);
    
        $pdf = PDF::loadView('generate-bill', compact('orderName', 'orderItems', 'totalPrice', 'totalQuantity', 'currentDateTime', 'orderDate', 'orderId','name','paymentMthd'));
        
        // Optional: Set PDF properties (title, author, etc.)
        $pdf->setPaper('A4', 'portrait'); // Set paper size and orientation

        $response = response($pdf->output())->header('Content-Type', 'application/pdf')
                                            ->header('Content-Disposition', 'inline; filename=invoice.pdf'); // Add the Content-Disposition header
    
        return $response;
    }
    


    public function clearOrder(){        

        if (session()->has('newOrder') && session()->has('orderToPaid') ){

            $newOrder = session('newOrder', []);

            $order = session('orderToPaid');
            $orderName = $order[0];
    
            $indexToBeDeleted = null;
            foreach ($newOrder as $index => $order) {
                if ($order[0] === $orderName) {
                    $indexToBeDeleted = $index;
                    break;
                }
            }
    
            if ($indexToBeDeleted !== null) {
                unset($newOrder[$indexToBeDeleted]);
    
                // Re-index the array numerically after the unset operation
                $newOrder = array_values($newOrder);
    
                session()->put('newOrder', $newOrder);
    
                if (empty($newOrder)) {
                    session()->forget('newOrder');
                    session()->save();
                }
            }
    
            session()->forget('orderToPaid');
            session()->forget('orderDate');
            session()->forget('orderId');        
            session()->forget('paymentMthd');        
    
            $categories = Category::with('products')->get();
            $productName = isset($_REQUEST['productName']) ? $_REQUEST['productName'] : '';
                        
            return view('transaction', compact('categories', 'productName'));

        }else{
            $categories = Category::with('products')->get();
            $productName = isset($_REQUEST['productName']) ? $_REQUEST['productName'] : '';
            return view('transaction', compact('categories', 'productName'));
        }


    
       
    }


    
}

