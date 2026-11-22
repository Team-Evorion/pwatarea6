<?php
namespace App\Services;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class OrderService {
    public function create(User $customer,array $items,?User $waiter=null): string {
        if(!$customer->hasRole('Client')) throw ValidationException::withMessages(['customer'=>'Selecciona una cuenta de cliente válida.']);
        $ids=collect($items)->pluck('dish_id')->map(fn($id)=>(int)$id)->all();
        if(count($ids)!==count(array_unique($ids))) throw ValidationException::withMessages(['items'=>'No se puede repetir un plato en la selección.']);
        return DB::transaction(function() use($customer,$items,$waiter,$ids){
            $dishes=Dish::whereIn('id',$ids)->get()->keyBy('id');
            if($dishes->count()!==count($ids)) throw ValidationException::withMessages(['items'=>'Uno o más platos ya no están disponibles.']);
            $group=(string)Str::uuid();
            foreach($items as $item){
                $dish=$dishes->get((int)$item['dish_id']);
                Order::create([
                    'user_id'=>$customer->id,'dish_id'=>$dish->id,'quantity'=>(int)$item['quantity'],
                    'unit_price'=>$dish->price,'status'=>Order::PENDING,
                    'order_group'=>$group,'waiter_id'=>$waiter?->id,
                ]);
            }
            return $group;
        });
    }
}
