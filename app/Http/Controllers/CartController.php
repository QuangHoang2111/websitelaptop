<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Voucher;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\UserVoucher;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $voucher = session()->get('voucher');

        return view('cart', compact('cart', 'subtotal', 'voucher'));
    }

  public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $stock = (int) $product->stocks;

        if ($stock <= 0 || $request->qty > $stock) {
            return response()->json(['error' => 'Vượt quá tồn kho'], 422);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['qty'] + $request->qty > $stock) {
                return response()->json(['error' => 'Vượt quá tồn kho'], 422);
            }
            $cart[$product->id]['qty'] += $request->qty;
        } else {
            $cart[$product->id] = [
                'name'  => $product->name,
                'price' => $product->saleprice > 0
                            ? $product->saleprice
                            : $product->regularprice,
                'qty'   => $request->qty,
                'stock' => $stock,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);
        session()->forget('voucher');

        if ($request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }

        return redirect()->route('cart.index');
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'qty'=> 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $id = $request->id;

        if (!isset($cart[$id])) {
            return response()->json(['error' => 'Không tồn tại'], 404);
        }

        $product = Product::findOrFail($id);
        $stock = (int) $product->stocks;

        if ($request->qty > $stock) {
            return response()->json(['error' => 'Vượt quá tồn kho'], 422);
        }

        $cart[$id]['qty'] = $request->qty;
        $cart[$id]['stock'] = $stock;

        session()->put('cart', $cart);
        session()->forget('voucher');

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);

        return response()->json([
            'success' => true,
            'subtotal'=> $subtotal
        ]);
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->id]);

        session()->put('cart', $cart);
        session()->forget('voucher');

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);

        return response()->json([
            'success' => true,
            'subtotal'=> $subtotal
        ]);
    }

        public function clear()
    {
        session()->forget('cart');
        session()->forget('voucher');

        return response()->json([
            'success' => true,
            'subtotal' => 0
        ]);
    }
    public function applyVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $userId = Auth::id();

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Giỏ hàng trống'], 422);
        }

        $voucher = Voucher::where('code', $request->code)
            ->whereDate('expdate', '>=', Carbon::today())
            ->first();

        if (!$voucher) {
            return response()->json(['error' => 'Voucher không hợp lệ'], 422);
        }

        if ($userId) {
            $used = UserVoucher::where('userid', $userId)
                ->where('voucherid', $voucher->id)
                ->whereNotNull('used_at')
                ->exists();

            if ($used) {
                return response()->json([
                    'error' => 'Voucher đã được sử dụng'
                ], 422);
            }
        }
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);

        $discount = $voucher->type === 'percent'
            ? round($subtotal * ($voucher->cartvalue / 100))
            : min($voucher->cartvalue, $subtotal);

        session()->put('voucher', [
            'code' => $voucher->code,
            'discount' => $discount
        ]);

        return response()->json([
            'success'  => true,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total'    => $subtotal - $discount
        ]);

    }

    public function checkout()
        {
            $cart = session()->get('cart', []);
            if (empty($cart)) {
                return redirect()->route('cart.index');
            }

            foreach ($cart as $productId => $item) {
                $stock = Product::where('id', $productId)->value('stocks');

                if ($item['qty'] > $stock) {
                    return redirect()->route('cart.index')->with('error', 'Sản phẩm vượt quá tồn kho');
                }
            }

            $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
            $voucher  = session()->get('voucher');
            $discount = $voucher['discount'] ?? 0;

            $address = Address::where('userid', Auth::id())->first();

            return view('checkout', compact(
                'cart',
                'subtotal',
                'discount',
                'address'
            ));
        }


    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'           => 'required|string',
            'phone'          => 'required|string',
            'address'        => 'required|string',
            'city'           => 'required|string',
            'ward'           => 'required|string',
            'payment_method' => 'required|in:COD,VNPAY',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);

        $voucherSession = session('voucher');
        $discount = $voucherSession['discount'] ?? 0;
        $total    = $subtotal - $discount;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'        => Auth::id(),
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'total'          => $total,
                'name'           => $request->name,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->city,
                'ward'           => $request->ward,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status'         => 'pending',
            ]);

            $remainingDiscount = $discount;
            $itemsCount = count($cart);
            $index = 0;

            foreach ($cart as $productId => $item) {
                $index++;

                $product = Product::findOrFail($productId);

                $itemTotal = $item['price'] * $item['qty'];
                $ratio = $subtotal > 0 ? ($itemTotal / $subtotal) : 0;

                if ($index === $itemsCount) {
                    $itemDiscount = $remainingDiscount;
                } else {
                    $itemDiscount = round($discount * $ratio);
                    $remainingDiscount -= $itemDiscount;
                }

                $finalUnitPrice = round(
                    ($itemTotal - $itemDiscount) / $item['qty']
                );

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'product_sku'  => $product->sku,
                    'price'        => $finalUnitPrice,
                    'cost_price'   => $product->costprice,
                    'quantity'     => $item['qty'],
                ]);
            }

            if ($voucherSession) {
                $voucher = Voucher::where('code', $voucherSession['code'])->first();
                if ($voucher) {
                    UserVoucher::updateOrCreate(
                        [
                            'userid'    => Auth::id(),
                            'voucherid' => $voucher->id,
                        ],
                        [
                            'used_at' => now(),
                        ]
                    );
                }
            }

            if ($request->payment_method === 'COD') {
                foreach ($cart as $productId => $item) {
                    $affected = Product::where('id', $productId)->where('stocks', '>=', $item['qty'])->decrement('stocks', $item['qty']);

                if ($affected === 0) {
                    throw new \Exception('Tồn kho không đủ');
                }
                }

                $order->update([
                    'payment_status' => 'unpaid',
                    'status'         => 'processing',
                ]);

                DB::commit();
                session()->forget(['cart', 'voucher']);

                return redirect()->route('home.index')->with('success', 'Đặt hàng COD thành công');
            }

            DB::commit();
            return redirect($this->createVnpayUrl($order));

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
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
