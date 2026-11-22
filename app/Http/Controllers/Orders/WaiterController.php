<?php
namespace App\Http\Controllers\Orders;
use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class WaiterController extends Controller {
    public function index(Request $request) {
        $orders=Order::with(['dish','user','waiter'])->where(function($q) use($request){
            $q->where('waiter_id',$request->user()->id)->orWhere('status',Order::READY);
        })->latest()->get();
        return view('orders.waiter',compact('orders'));
    }
    public function create() {
        return view('orders.waiter-create',['clients'=>User::whereHas('role',fn($q)=>$q->where('name','Client'))->orderBy('name')->get(),
                                         'dishes'=>Dish::orderBy('name')->get()]);
    }
    public function store(Request $request,OrderService $service) {
        $raw=$request->input('items_json'); $items=is_string($raw)?json_decode($raw,true):null;
        $data=Validator::make(['client_id'=>$request->input('client_id'),'items'=>$items],[
            'client_id'=>'required|integer|exists:users,id',
            'items'=>'required|array|min:1|max:20',
            'items.*.dish_id'=>'required|integer|distinct|exists:dishes,id',
            'items.*.quantity'=>'required|integer|min:1|max:50',
        ])->validate();
        $client=User::with('role')->findOrFail($data['client_id']);
        $service->create($client,$data['items'],$request->user());
        return redirect()->route('waiter.index')->with('success','Pedido registrado para el cliente.');
    }
    public function serve(Request $request,Order $order) {
        DB::transaction(function() use($order,$request){
            $locked=Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if($locked->status!==Order::READY) throw ValidationException::withMessages(['status'=>'Solo se pueden servir pedidos listos.']);
            if($locked->waiter_id && $locked->waiter_id!==$request->user()->id) abort(403);
            $locked->update(['status'=>Order::SERVED,'waiter_id'=>$request->user()->id]);
        });
        return redirect()->route('waiter.index')->with('success','Pedido marcado como servido.');
    }
}
