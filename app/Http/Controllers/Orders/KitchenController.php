<?php
namespace App\Http\Controllers\Orders;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class KitchenController extends Controller {
    public function index() {
        return view('orders.kitchen',['orders'=>Order::with(['dish','user'])->orderBy('created_at')->get()]);
    }
    public function transition(Order $order,string $action) {
        $map=['start'=>[Order::PENDING,Order::PREPARING], 'ready'=>[Order::PREPARING,Order::READY]];
        abort_unless(isset($map[$action]),404);
        DB::transaction(function() use($order,$map,$action){
            $locked=Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            [$from,$to]=$map[$action];
            if($locked->status!==$from) throw ValidationException::withMessages(['status'=>'El pedido cambió de estado. Actualiza la página.']);
            $locked->update(['status'=>$to]);
        });
        return redirect()->route('kitchen.index')->with('success','Estado actualizado.');
    }
}
