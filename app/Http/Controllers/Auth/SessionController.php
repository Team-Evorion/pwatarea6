<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class SessionController extends Controller {
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }
    public function register(Request $request) {
        $data=$request->validate([
          'name'=>'required|string|max:100', 'username'=>'required|string|alpha_dash|max:50|unique:users,username',
          'email'=>'required|email|max:100|unique:users,email',
          'password'=>['required','confirmed',Password::min(8)],
        ]);
        $role=Role::where('name','Client')->firstOrFail();
        $user=User::create([
          'name'=>$data['name'], 'username'=>$data['username'],'email'=>$data['email'],
          'password'=>Hash::make($data['password']), 'role_id'=>$role->id,
        ]);
        Auth::login($user); $request->session()->regenerate();
        return redirect()->route('dishes.catalog')->with('success','¡Bienvenido! Ya puedes realizar pedidos.');
    }
    public function login(Request $request) {
        $credentials=$request->validate(['email'=>'required|email','password'=>'required|string','remember'=>'sometimes|boolean']);
        $remember=(bool)($credentials['remember']??false);
        if(!Auth::attempt(['email'=>$credentials['email'],'password'=>$credentials['password']],$remember)){
            return back()->withErrors(['email'=>'El correo o la contraseña no son correctos.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        if($request->input('next')==='checkout') return redirect()->route('dishes.catalog')->with('checkout_after_login',true);
        return redirect()->route('home.for-role');
    }
    public function roleHome(Request $request) {
        $user=$request->user();
        if($user->hasRole('Administrator')) return redirect()->route('admin.dashboard');
        if($user->hasRole('Chef')) return redirect()->route('kitchen.index');
        if($user->hasRole('Waiter')) return redirect()->route('waiter.index');
        return redirect()->route('dishes.catalog');
    }
    public function logout(Request $request) {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('dishes.catalog')->with('success','Sesión cerrada correctamente.');
    }
}
