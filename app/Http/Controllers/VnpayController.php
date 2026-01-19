<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VnpayController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->payment_method !== 'VNPAY') {
            return back()->with('error', 'Đơn hàng không dùng VNPAY');
        }

        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            return back()->with('error', 'Đơn hàng không thể thanh toán lại');
        }

        return redirect($this->createVnpayUrl($order));
    }

   public function return(Request $request)
{
    $orderId = $request->vnp_TxnRef;
    $order = Order::with('items')->find($orderId);

    if (!$order) {
        return redirect()->route('home.index');
    }

    if ($request->vnp_ResponseCode !== '00') {
        return redirect()
            ->route('user.orders.show', $order)
            ->with('error', 'Thanh toán VNPAY thất bại');
    }

    if ($order->payment_status === 'paid') {
        return redirect()
            ->route('user.orders.show', $order)
            ->with('success', 'Đơn hàng đã được thanh toán');
    }

    DB::transaction(function () use ($order) {

        foreach ($order->items as $item) {
            $affected = Product::where('id', $item->product_id)
                ->where('stocks', '>=', $item->quantity)
                ->decrement('stocks', $item->quantity);

            if ($affected === 0) {
                throw new \Exception('Tồn kho không đủ');
            }
        }

        $order->update([
            'payment_status' => 'paid',
            'status'         => 'processing',
        ]);
    });

    return redirect()
        ->route('user.orders.show', $order)
        ->with('success', 'Thanh toán VNPAY thành công');
}


    private function createVnpayUrl(Order $order)
    {
        $vnp_Url        = config('services.vnpay.url');
        $vnp_ReturnUrl = config('services.vnpay.return_url');
        $vnp_TmnCode   = config('services.vnpay.tmn_code');
        $vnp_HashSecret= config('services.vnpay.hash_secret');

        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_Command"   => "pay",
            "vnp_TmnCode"   => $vnp_TmnCode,
            "vnp_Amount"    => $order->total * 100,
            "vnp_CurrCode"  => "VND",
            "vnp_TxnRef"    => (string)$order->id,
            "vnp_OrderInfo" => "Thanh toan don hang #" . $order->id,
            "vnp_OrderType" => "other",
            "vnp_Locale"    => "vn",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_IpAddr"    => request()->ip(),
            "vnp_CreateDate"=> now()->format('YmdHis'),
        ];

        ksort($inputData);

        $hashData = "";
        $query = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . "&";
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        return $vnp_Url . "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;
    }
}
