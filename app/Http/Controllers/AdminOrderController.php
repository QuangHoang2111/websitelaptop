<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderByDesc('id')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->status === 'cancelled') {
        return back()->with('error', 'Đơn hàng đã bị huỷ, không thể thay đổi trạng thái');
        }
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        DB::transaction(function () use ($request, $order) {

            if ($request->status === 'cancelled' && $order->status !== 'cancelled') {

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stocks', $item->quantity);
                    }
                }

                $order->cancelled_date = now();
            }

            $order->status = $request->status;
            $order->save();
        });

        return back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function updateOrderDate(Request $request, Order $order)
    {
         $request->validate([
        'delivered_date' => 'nullable|date',
        'cancelled_date' => 'nullable|date',
        ]);

        $order->update([
            'delivered_date' => $request->delivered_date ?:null,
            'cancelled_date' => $request->cancelled_date ?:null,
        ]);

        return back()->with('success', 'Cập nhật ngày thành công');
    }
}
