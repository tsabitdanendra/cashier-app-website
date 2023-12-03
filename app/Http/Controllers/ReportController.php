<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Product; 
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{

    public function generateReport(Request $request)
    {
        $startDate = $request->input('startdate');
        $endDate = $request->input('enddate');
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        // Create an instance of the ExcelExport class with the date range
        $dataExport = new ExcelExport($startDate, $endDateTime);

        return Excel::download($dataExport, 'data.xlsx');
    }
    
    public function showReportPage(){
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        $startDate->setTime(00, 00, 00);    
        $endDate->setTime(23, 59, 59);

        $startDatePeriod = date('Y-m-d', $startDate->timestamp);
        $endDatePeriod = date('Y-m-d', $endDate->timestamp);


        // $endDateDt = date('Y-m-d', $newEndDateTimestamp);

    
        // Convert end date to a UNIX timestamp
        // $endDateTimestamp = strtotime($endDate);

        // Calculate the timestamp for one day and one second
        // $oneDay = 86400; // Number of seconds in a day
        // $oneSecond = 1;

        // $newEndDateTimestamp = $endDateTimestamp + $oneDay - $oneSecond;

        // // Format the new end date
        // $newEndDate = date('Y-m-d H:i:s', $newEndDateTimestamp);

        // @dd($newEndDate);

        // Fetch orders with order details within the manipulated date range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->with('details') // Eager load order details
            ->get();

        if ($orders->isEmpty()) {
            // If no orders, set the data to 0
            $orders = 0;
            $totalTrans = 0;
            $totalProd = 0;
            $totalPrice = 0;
            $formattedTotalPrice = 0;
            $mostUsedPaymentMethod = NULL;
            $topOrderedProductsInfo = Null;
            $productNamesAndQuantities = NULL;
        } else { 

            // put data into variable
        //============================ put data into variable ==================================
        foreach ($orders as $order) {
            $orderId[] = $order->id;
            $orderName = $order->orderName;
            $totalQty[] = $order->totalQty;
            $paymentMthdDb[] = $order->paymentMthd;
            $totalPriceDb[] = $order->totalPrice;    
        }

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $productId[] = $detail->product_id;
            
            }
        }
        //============================ put data into variable ==================================

        $totalTrans = count($orderId); // Total Transaksi
        //============================ Total produk dipesan ==================================
        $totalProd = 0;
        foreach ($totalQty as $qty){
            $totalProd += $qty;
        }
        //============================ Total produk dipesan ==================================

        //============================ Total pendapatan ==================================
        $totalPrice = 0;
         foreach ($totalPriceDb as $price) {
            $totalPrice += $price;
         }

         $formattedTotalPrice = 'Rp ' . number_format($totalPrice, 0, ',', '.');     
        //============================ Total pendapatan ==================================

        //============================ Metode Pembayaran paling banyak ==================================

        $paymentCounts = array_count_values($paymentMthdDb);
        
        $mostUsedPaymentMethod = [];

        foreach ($paymentCounts as $method => $count) {
            $paymentQuantities[] = [
                'method' => $method,
                'quantity' => $count,
            ];        

        }
        // Sort the paymentQuantities array by quantity in descending order
        usort($paymentQuantities, function ($a, $b) {
            return $b['quantity'] - $a['quantity'];
        });

        $mostUsedPaymentMethod = $paymentQuantities;
        //============================ Metode Pembayaran paling banyak ==================================

        //============================ Top produk yang dibeli paling banyak ==================================
        $topProducts = OrderDetail::whereIn('product_id', $productId)
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('product_id')
        ->select('product_id', \DB::raw('SUM(productQuantity) as totalQuantity'))
        ->orderByDesc('totalQuantity')
        ->limit(5)
        ->get();

        $productNamesAndQuantities = [];
        foreach ($topProducts as $product) {
            $productModel = Product::find($product->product_id);
            if ($productModel) {
                $productNamesAndQuantities[] = [
                    'name' => $productModel->name,
                    'totalQuantity' => $product->totalQuantity,
                ];
            }
        }
        // @dd($productId);
        // $productCounts = array_count_values($productId);

        // // Create an array to store top ordered products and their counts
        // $topOrderedProducts = [];

        // foreach ($productCounts as $productId => $count) {
        //     // Check if the current product has a higher count than any of the top products
        //     if (count($topOrderedProducts) < 3 || $count > $topOrderedProducts[2]['count']) {
        //         // Add the current product to the top products array
        //         $topOrderedProducts[] = [
        //             'product_id' => $productId,
        //             'count' => $count,
        //         ];

        //         // Sort the top products array based on counts in descending order
        //         usort($topOrderedProducts, function ($a, $b) {
        //             return $b['count'] - $a['count'];
        //         });

        //         // If the array now has more than 3 products, remove the last one
        //         if (count($topOrderedProducts) > 3) {
        //             array_pop($topOrderedProducts);
        //         }
        //     }
        // }

        // // Retrieve product names based on product IDs
        // $productNames = Product::whereIn('id', array_column($topOrderedProducts, 'product_id'))
        // ->pluck('name', 'id')
        // ->toArray();
    

        // @dd($topProductsWithDetails);

        // Create an array to store top ordered products with names and counts
        // $topOrderedProductsInfo = [];

        // foreach ($topProducts as $topProd) {
        //     $productInfo = [
        //         'product_id' => $topProducts['product_id'],
        //         'name' => $productNames[$topProd['product_id']],
        //         'count' => $topProd['count'],
        //     ];
        //     $topOrderedProductsInfo[] = $productInfo;
        // }
        //============================ Top produk yang dibeli paling banyak ==================================

        
        // @dd($productNamesAndQuantities);
        }

        return view('report', [
        'orders' => $orders,
        'startDate' => $startDatePeriod,
        'endDate' => $endDatePeriod,
        'totalTrans' => $totalTrans,
        'totalProd' => $totalProd,
        'totalPrice' => $formattedTotalPrice,
        'mostUsedPaymentMethod' => $mostUsedPaymentMethod,
        'topOrderedProductsInfo' => $productNamesAndQuantities,
    ]);
    }



    public function fetchReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        

        // @dd($startDate);
        // Convert end date to a UNIX timestamp
        $endDateTimestamp = strtotime($endDate);

        // Calculate the timestamp for one day and one second
        $oneDay = 86400; // Number of seconds in a day
        $oneSecond = 1;

        $newEndDateTimestamp = $endDateTimestamp + $oneDay - $oneSecond;

        // Format the new end date
        $newEndDate = date('Y-m-d H:i:s', $newEndDateTimestamp);

        // @dd($newEndDate);

        // Fetch orders with order details within the manipulated date range
        $orders = Order::whereBetween('created_at', [$startDate, $newEndDate])
            ->with('details') // Eager load order details
            ->get();

        if ($orders->isEmpty()) {
            // If no orders, set the data to 0
            $orders = 0;
            $totalTrans = 0;
            $totalProd = 0;
            $totalPrice = 0;
            $formattedTotalPrice = 0;
            $mostUsedPaymentMethod = NULL;
            $topOrderedProductsInfo = NULL;
            $productNamesAndQuantities = NULL;
        } else { 

            // put data into variable
        //============================ put data into variable ==================================
        foreach ($orders as $order) {
            $orderId[] = $order->id;
            $orderName = $order->orderName;
            $totalQty[] = $order->totalQty;
            $paymentMthdDb[] = $order->paymentMthd;
            $totalPriceDb[] = $order->totalPrice;    
        }

        foreach ($orders as $order) {
            foreach ($order->details as $detail) {
                $productId[] = $detail->product_id;
            
            }
        }
        //============================ put data into variable ==================================

        $totalTrans = count($orderId); // Total Transaksi
        //============================ Total produk dipesan ==================================
        $totalProd = 0;
        foreach ($totalQty as $qty){
            $totalProd += $qty;
        }
        //============================ Total produk dipesan ==================================

        //============================ Total pendapatan ==================================
        $totalPrice = 0;
         foreach ($totalPriceDb as $price) {
            $totalPrice += $price;
         }

         $formattedTotalPrice = 'Rp ' . number_format($totalPrice, 0, ',', '.');     
        //============================ Total pendapatan ==================================

        //============================ Metode Pembayaran paling banyak ==================================
        $paymentCounts = array_count_values($paymentMthdDb);
        
        $mostUsedPaymentMethod = [];

        foreach ($paymentCounts as $method => $count) {
            $paymentQuantities[] = [
                'method' => $method,
                'quantity' => $count,
            ];        

        }
        // Sort the paymentQuantities array by quantity in descending order
        usort($paymentQuantities, function ($a, $b) {
            return $b['quantity'] - $a['quantity'];
        });

        $mostUsedPaymentMethod = $paymentQuantities;
        // @dd($mostUsedPaymentMethod);
        //============================ Metode Pembayaran paling banyak ==================================

        //============================ Top produk yang dibeli paling banyak ==================================
        // @dd($startDate, $newEndDate);
        $topProducts = OrderDetail::whereIn('product_id', $productId)
        ->whereBetween('created_at', [$startDate, $newEndDate])
        ->groupBy('product_id')
        ->select('product_id', \DB::raw('SUM(productQuantity) as totalQuantity'))
        ->orderByDesc('totalQuantity')
        ->limit(5)
        ->get();

        $productNamesAndQuantities = [];
        foreach ($topProducts as $product) {
            $productModel = Product::find($product->product_id);
            if ($productModel) {
                $productNamesAndQuantities[] = [
                    'name' => $productModel->name,
                    'totalQuantity' => $product->totalQuantity,
                ];
            }
        }
        
        // @dd($productNamesAndQuantities);

        // $productCounts = array_count_values($productId);

        // // Create an array to store top ordered products and their counts
        // $topOrderedProducts = [];

        // foreach ($productCounts as $productId => $count) {
        //     // Check if the current product has a higher count than any of the top products
        //     if (count($topOrderedProducts) < 3 || $count > $topOrderedProducts[2]['count']) {
        //         // Add the current product to the top products array
        //         $topOrderedProducts[] = [
        //             'product_id' => $productId,
        //             'count' => $count,
        //         ];

        //         // Sort the top products array based on counts in descending order
        //         usort($topOrderedProducts, function ($a, $b) {
        //             return $b['count'] - $a['count'];
        //         });

        //         // If the array now has more than 3 products, remove the last one
        //         if (count($topOrderedProducts) > 3) {
        //             array_pop($topOrderedProducts);
        //         }
        //     }
        // }


        // @dd($topOrderedProductsInfo);  

        //============================ Top produk yang dibeli paling banyak ==================================

        
        // @dd($topOrderedProductsInfo);
        }

        return view('report', [
        'orders' => $orders,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'totalTrans' => $totalTrans,
        'totalProd' => $totalProd,
        'totalPrice' => $formattedTotalPrice,
        'mostUsedPaymentMethod' => $mostUsedPaymentMethod,
        'topOrderedProductsInfo' => $productNamesAndQuantities,
    ]);
    }

}
