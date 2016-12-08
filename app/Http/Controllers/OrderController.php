<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;
use App\Events\NewOrder;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'cook_time' => 'required|integer',
        ]);

        $order = Order::create([
            'user_id' => Auth::user()->id,
            'table_number' => $request->table_number,
            'name' => $request->name,
            'status' => 'cooking',
            'cook_time' => date( 'Y-m-d H:i:s', strtotime('+ ' . $request->cook_time . ' minutes')),
        ]);

        event(new \App\Events\NewOrder($order, Auth::user()));

        return $order;
    }

    public function changeStatus(Request $request)
    {
        $data = [
            'status' => $request->order_status
        ];
        $order = Order::find($request->order_id);
        $order->fill($data);
        if ($order->update()) {
            event(new \App\Events\OrderStatusChange($order));
            return $order;
        }

        return false;
    }

    public function deleteOrder(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->delete();

        return  $order;
    }
}
