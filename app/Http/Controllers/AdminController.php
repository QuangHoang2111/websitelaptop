<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }


    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('sku', 'LIKE', "%{$query}%");
        })->get();

        return response()->json($products);
    }

    public function users()
    {
        $users = User::orderByDesc('id')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->id === auth::id()) {
            return back();
        }

        $request->validate([
            'utype' => 'required|in:ADM,USR',
        ]);

        $user->update([
            'utype' => $request->utype,
        ]);

        return redirect()->route('admin.users')->with('success', 'Cập nhật quyền thành công');

    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'from' => Carbon::now()->startOfMonth(),
            'to'   => Carbon::now()->endOfMonth(),
        ]);
    }

    public function dashboardData(Request $request)
    {
        $from = $request->from
            ? Carbon::parse($request->from)->startOfDay()
            : Carbon::now()->startOfMonth();

        $to = $request->to
            ? Carbon::parse($request->to)->endOfDay()
            : Carbon::now()->endOfMonth();

        $ordersQuery = Order::where('status', 'completed') -> whereNotNull('delivered_date')
            ->whereBetween('delivered_date', [$from, $to]);

        $totalOrders = (clone $ordersQuery)->count();

        $totalRevenue = (clone $ordersQuery)->where('status', 'completed')->sum('total');

        $totalProfit = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.delivered_date', [$from, $to])
            ->selectRaw('
                SUM((order_items.price - order_items.cost_price) * order_items.quantity)
                AS profit
            ')
            ->value('profit') ?? 0;

        $dailyRevenue = Order::selectRaw('
                DATE(delivered_date) as date,
                SUM(total) as revenue
            ')
            ->where('status', 'completed')
            ->whereBetween('delivered_date', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyProfit = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.delivered_date', [$from, $to])
            ->selectRaw('
                DATE(orders.delivered_date) as date,
                SUM((order_items.price - order_items.cost_price) * order_items.quantity) as profit
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $statusStats = Order::selectRaw('status, COUNT(*) as total')->whereBetween('created_at', [$from, $to])
            ->groupBy('status')->pluck('total', 'status');

        return response()->json([
            'totalOrders'  => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalProfit'  => $totalProfit,
            'labels'       => $dailyRevenue->pluck('date'),
            'revenue'      => $dailyRevenue->pluck('revenue'),
            'profit'       => $dailyProfit->pluck('profit'),
            'status'       => $statusStats,
        ]);
    }

   
    public function updateOrderDate(Request $request, $order)
    {
        $request->validate([
            'delivered_date' => 'required|date',
        ]);

        $order = Order::findOrFail($order);

        $order->update([
            'delivered_date' => $request->delivered_date,
        ]);

        return back()->with('success', 'Cập nhật ngày giao hàng thành công');
    }
}
