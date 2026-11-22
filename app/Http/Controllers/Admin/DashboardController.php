<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Order;
use App\Models\User;
class DashboardController extends Controller {
    public function index() {
        $users=User::with('role')->get();
        $stats=[
          'users'=>$users->count(), 'clients'=>$users->filter(fn($u)=>$u->hasRole('Client'))->count(),
          'chefs'=>$users->filter(fn($u)=>$u->hasRole('Chef'))->count(),
          'waiters'=>$users->filter(fn($u)=>$u->hasRole('Waiter'))->count(),
          'dishes'=>Dish::count(), 'orders'=>Order::count(),
          'pending'=>Order::where('status',Order::PENDING)->count(),
          'preparing'=>Order::where('status',Order::PREPARING)->count(),
          'ready'=>Order::where('status',Order::READY)->count(),
          'served'=>Order::where('status',Order::SERVED)->count(),
        ];
        $recent=Order::with(['dish','user'])->latest()->take(8)->get();
        return view('admin.dashboard', compact('stats','recent'));
    }
}
