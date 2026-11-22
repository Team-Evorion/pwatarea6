<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
class OrderOverviewController extends Controller {
    public function index(Request $request) {
        $status=$request->query('status');
        $query=Order::with(['dish','user','waiter'])->latest();
        if(in_array($status,Order::STATUSES,true)) $query->where('status',$status);
        return view('admin.orders',['orders'=>$query->paginate(15)->withQueryString(),'status'=>$status]);
    }
}
