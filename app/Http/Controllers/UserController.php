<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index()
    {
        return view('user.index');
    }

    public function profile()
    {
        $user = Auth::user();
        $address = $user->address;

        return view('user.profile', compact('address'));
    }


    public function orders()
    {
        return view('user.orders');
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'phone'   => 'nullable',
            'address' => 'nullable',
            'ward'    => 'nullable',
            'city'    => 'nullable',
        ]);

        $user = Auth::user();
        $address = $user->address;


        if ($address) {
            $address->update($request->only([
                'name', 'phone', 'address', 'ward', 'city'
            ]));
        } else {
            Address::create([
                'userid'    => Auth::id(),
                'name'      => $request->name,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'ward'      => $request->ward,
                'city'      => $request->city,
            ]);
        }

        return redirect()
            ->route('user.profile')
            ->with('success', 'Lưu địa chỉ thành công');
    }


    public function orderList()
    {
         $orders = Order::where('user_id', Auth::id())
        ->where('status', '!=', 'cancelled')
        ->orderByDesc('created_at')
        ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('user.orderdetail', compact('order'));
    }


    public function cancelOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status === 'completed') {
            return back()->with('error', 'Không thể huỷ đơn hàng này');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Đơn hàng đã bị huỷ trước đó');
        }

        DB::transaction(function () use ($order) {

            $order->load('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stocks', $item->quantity);
                }
            }

            $order->status = 'cancelled';
            $order->cancelled_date = now();
            $order->save();
        });

        return back()->with('success', 'Đã huỷ đơn hàng');
    }

    public function changePasswordForm()
    {
        return view('user.changepassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng');
        }
        
        /** @var \App\Models\User $user */
    
        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công');
    }
}
