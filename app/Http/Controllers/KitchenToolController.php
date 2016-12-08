<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class KitchenToolController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::where('status', '<>', 'done')->get();

        return view('kitchen.index', ['orders' => $orders]);
    }
}
