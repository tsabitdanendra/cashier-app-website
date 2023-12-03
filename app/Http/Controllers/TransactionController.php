<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class TransactionController extends Controller
{

    public function showTransactionPage()
    {
        session()->forget('orderToPaid');
        
        $categories = Category::with('products')->get();
        $productName = isset($_REQUEST['productName']) ? $_REQUEST['productName'] : '';
        

        return view('transaction', compact('categories', 'productName'));
        
    }


    public function payButton(Request $request)
    {
        $orderName = $request->input('orderName');
        return redirect()->route('payment')->with('orderName', $orderName);

        // if ($orderName != '') {
        //     // Order name is not empty
        //     // Perform further actions or redirect to the payment view
        //     return redirect()->route('payment')->with('orderName', $orderName);
        // } else {
        //     // Order name is empty
        //     // Show an error message or redirect back with an error
        //     return back();
        // }
    }

    public function addToOrderList(Request $request){
        $orderName = $request->input('orderName');
        session()->flash('successO', 'New order added to the order list.');
        return back();
    }

    public function submitButton(Request $request)
    {
        
        // $productId = $request->input('productId');
        // $productName = $request->input('productName');
        // $productPrice = $request->input('productPrice');
        // // $productPrice = $request->input('productPrice');
        // // $quantityInput = $request->input('quantityInput');


        // return response()->json(['productName' => $productName, 'productPrice' => $productPrice]); 

        // $productName = $request->input('productName');
        // $productPrice = $request->input('productPrice');
        // $productPrice = $request->input('productPrice');

        $productDetails = [
            'productName' => $request->input('productName'),
            'productPrice' => $request->input('productPrice'),
            'productQuantity' => $request->input('productQuantity'),
            'productId' => $request->input('productId'),
        ];

        return response()->json(['productDetails' => $productDetails]);

        
    }

    public function addToSession(Request $request)
    {
        $orderName = $request->input('orderName');
        $newProduct = $request->input('newProduct');

        // @dd($newProduct);

        // Get the existing session data
        $newOrder = $request->session()->get('newOrder', []);

        // Find the specific array in the session and push the new value
        foreach ($newOrder as &$order) {
            if ($order[0] === $orderName) {
                for ($i = 0; $i < count($newProduct); $i++) {
                    if (!in_array($newProduct[$i], $order[1])) {
                        $order[1][] = $newProduct[$i]; // Add the value of $newProduct to the existing order array
                    }
                }
                break;
            }
        }
        

        // Save the updated session data
        $request->session()->put('newOrder', $newOrder);

        return response()->json(['message' => 'Data added to session successfully']);
    }

    public function directToPayment(Request $request){
        
        $newOrderData = $request->input('newOrder');
        $newOrderName = $request->input('newOrderName'); 
        

        if (session()->has('newOrder')) {
            $existingOrderData = session('newOrder');
            $existingOrderData[] = $newOrderData;
            session()->put('newOrder', $existingOrderData);
            
        } else {
            session()->put('newOrder', [$newOrderData]);
            
        }

        $newOrder = session('newOrder', []);

        // Find the index of the specific 'dltOrderName'
        $orderIndex = null;
        foreach ($newOrder as $index => $order) {
            if ($order[0] === $newOrderName) {
                $orderIndex = $index;
                break;
            }
        }

        $orderToPaid = $newOrder[$orderIndex];
        // @dd($orderToPaid);

        return response()->json([
            'newOrder' => $newOrder,
            'orderToPaid' => $orderToPaid,
        ]);
        
    }


    

    
}
