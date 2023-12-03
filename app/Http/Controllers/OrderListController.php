<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;


class OrderListController extends Controller
{

    

    public function showOrderListPage()
    {
        // Access $this->newOrder to use the value in this function
        // $newOrder = $this->newOrder;
        
        return view('orderList');

        
    }

   

    public function addNewOrder(Request $request)
    {
        $newOrderData = $request->input('newOrder');

        if (session()->has('newOrder')) {
            $existingOrderData = session('newOrder');
            $existingOrderData[] = $newOrderData;
            session()->put('newOrder', $existingOrderData);
            session()->flash('successO', 'New order added to the order list.');
        } else {
            session()->put('newOrder', [$newOrderData]);
            session()->flash('successO', 'New order added to the order list.');
        }

        return response()->json(['newOrder' => $newOrderData]);
    }

    public function dltProduct(Request $request)
{
    $dltOrderName = $request->input('dltOrderName');
    $deletedIndex = $request->input('deletedIndex');

    // Get the 'newOrder' session data
    $newOrder = session('newOrder', []);

    // Find the index of the specific 'dltOrderName'
    $indexToBeDeleted = null;
    foreach ($newOrder as $index => $order) {
        if ($order[0] === $dltOrderName) {
            $indexToBeDeleted = $index;
            break;
        }
    }

    // If the 'dltOrderName' was found, delete the product at 'deletedIndex'
    if ($indexToBeDeleted !== null) {
        unset($newOrder[$indexToBeDeleted][1][$deletedIndex]);

        // If the 'newOrder' array is empty after deleting the product, remove the entire order
        if (empty($newOrder[$indexToBeDeleted][1])) {
            unset($newOrder[$indexToBeDeleted]);
        } else {
            // Re-index the array to remove gaps
            $newOrder[$indexToBeDeleted][1] = array_values($newOrder[$indexToBeDeleted][1]);
        }

        // Re-index the 'newOrder' array to remove gaps after unsetting an element
        $newOrder = array_values($newOrder);

        // Save the updated 'newOrder' session data
        session()->put('newOrder', $newOrder);

        // If 'newOrder' array is completely empty, delete the 'newOrder' session
        if (empty($newOrder)) {
            session()->forget('newOrder');
            session()->save();
        }
    }

    return response()->json(['message' => 'Data deleted successfully']);
}


    public function dltOrder(Request $request)
{
    $cancelOrderName = $request->input('cancelOrderName');

    // Get the 'newOrder' session data
    $newOrder = session('newOrder', []);

    // Find the index of the specific 'dltOrderName'
    $indexToBeDeleted = null;
    foreach ($newOrder as $index => $order) {
        if ($order[0] === $cancelOrderName) {
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

    return response()->json(['message' => 'Data deleted successfully']);
}


    public function toPayment(Request $request)
    {
        $payOrderName = $request->input('payOrderName');  

        // Get the 'newOrder' session data
        $newOrder = session('newOrder', []);

        // Find the index of the specific 'dltOrderName'
        $orderIndex = null;
        foreach ($newOrder as $index => $order) {
            if ($order[0] === $payOrderName) {
                $orderIndex = $index;
                break;
            }
        }

        $orderToPaid = $newOrder[$orderIndex];

        // Return the orderToPaid data as JSON response
        return response()->json(['orderToPaid' => $orderToPaid]);
    }






    

}

// insert data to database
 // // dd(request()->all());
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
