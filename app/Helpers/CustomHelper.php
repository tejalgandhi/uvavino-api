<?php


use Illuminate\Support\Facades\Config;
use App\Models\Order;

/*
 * This function is used for the backend crud that store the images in the backend
 * and get the images from that folder
 * */
if (!function_exists('getFileUploadPath')) {
    function getFileUploadPath($file)
    {
        if (!empty($file)) {
            return config('constant.upload_url').$file;
        }
    }
}

if (!function_exists('generateOrderNo')) {
    function generateOrderNo()
    {
        // Get the latest order, if it exists
        $latestOrder = Order::latest()->first();

        if (!empty($latestOrder)) {
            // If the latest order has no order number, assign order numbers to all orders
            if (empty($latestOrder->order_no)) {
                $orders = Order::get();
                $order_no = 1111;

                foreach ($orders as $order) {
                    $order->update(['order_no' => $order_no]);
                    $order_no++;
                }

                return $order_no;
            }

            // Increment the latest order number by 1
            return $latestOrder->order_no + 1;
        }

        // No orders exist, start with order number 1000
        return 1111;
    }
}
