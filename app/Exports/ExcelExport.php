<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExcelExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->select(
                'orders.created_at',
                'orders.id as order_id',
                'orders.orderName',
                'orders.user_id',
                'users.name as user_name',
                'order_details.product_id',
                'products.name as product_name',
                'order_details.productQuantity',
                'products.price',
                'orders.paymentMthd',
                'orders.totalQty',
                'orders.totalPrice',
                'orders.status'
            )
            ->get();

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Order ID',
            'Order Name',
            'User ID',
            'User Name',
            'Product ID',
            'Product Name',
            'Product Quantity',
            'Product Price',
            'Payment Method',
            'Total Quantity',
            'Total Price',
            'Status',
        ];
    }
}
