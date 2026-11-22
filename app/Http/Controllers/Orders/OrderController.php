<?php
namespace App\Http\Controllers\Orders;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller {
    public function index(Request $request) {
        $orders=Order::with('dish')->where('user_id',$request->user()->id)->latest()->get();
        $groups=$orders->groupBy('order_group');
        return view('orders.history',compact('groups'));
    }
    public function store(Request $request,OrderService $service) {
        $raw=$request->input('items',$request->input('items_json'));
        $items=is_string($raw)?json_decode($raw,true):$raw;
        $data=Validator::make(['items'=>$items],[
            'items'=>'required|array|min:1|max:20',
            'items.*.dish_id'=>'required|integer|distinct|exists:dishes,id',
            'items.*.quantity'=>'required|integer|min:1|max:50',
        ])->validate();
        $group=$service->create($request->user(),$data['items']);
        return redirect()->route('orders.show',['group'=>$group])->with('success','¡Pedido realizado correctamente!');
    }
    public function show(Request $request,string $group) {
        $orders=Order::with('dish')->where('user_id',$request->user()->id)->where('order_group',$group)->get();
        abort_if($orders->isEmpty(),404);
        return view('orders.show',compact('orders','group'));
    }
}
