<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
class UserController extends Controller {
    public function index(Request $request) {
        $query=User::query()->with('role');
        if($term=trim((string)$request->input('q',''))) $query->where(function($q) use($term){
            $q->where('name','like',"%{$term}%")->orWhere('email','like',"%{$term}%")->orWhere('username','like',"%{$term}%");
        });
        if($role=$request->input('role')) $query->whereHas('role',fn($q)=>$q->where('name',$role));
        return view('admin.users.index',['users'=>$query->latest()->paginate(12)->withQueryString(),'roles'=>Role::orderBy('name')->get()]);
    }
    public function create() { return view('admin.users.form',['user'=>new User(),'roles'=>Role::orderBy('name')->get()]); }
    public function store(Request $request) {
        $data=$request->validate([
            'name'=>'required|string|max:100','username'=>'required|alpha_dash|max:50|unique:users,username',
            'email'=>'required|email|max:100|unique:users,email',
            'password'=>['required','confirmed',Password::min(8)], 'role_id'=>'required|exists:roles,id',
        ]);
        $data['password']=Hash::make($data['password']); User::create($data);
        return redirect()->route('admin.users.index')->with('success','Usuario creado correctamente.');
    }
    public function edit(User $user) { return view('admin.users.form',['user'=>$user,'roles'=>Role::orderBy('name')->get()]); }
    public function update(Request $request, User $user) {
        $data=$request->validate([
            'name'=>'required|string|max:100','username'=>['required','alpha_dash','max:50',Rule::unique('users','username')->ignore($user->id)],
            'email'=>['required','email','max:100',Rule::unique('users','email')->ignore($user->id)],
            'password'=>['nullable','confirmed',Password::min(8)], 'role_id'=>'required|exists:roles,id',
        ]);
        DB::transaction(function() use($user,$data) {
            $locked=User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            $selected=Role::findOrFail($data['role_id']);
            if($locked->hasRole('Administrator') && $selected->name!=='Administrator') $this->preventLastAdmin();
            if(empty($data['password'])) unset($data['password']); else $data['password']=Hash::make($data['password']);
            $locked->update($data);
        });
        return redirect()->route('admin.users.index')->with('success','Usuario actualizado correctamente.');
    }
    public function destroy(User $user) {
        DB::transaction(function() use($user) {
            $locked=User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if($locked->id===auth()->id()) throw ValidationException::withMessages(['user'=>'No puedes eliminar tu propia cuenta.']);
            if($locked->hasRole('Administrator')) $this->preventLastAdmin();
            if($locked->orders()->exists()) throw ValidationException::withMessages(['user'=>'Este usuario tiene pedidos y no puede eliminarse.']);
            if($locked->assignedOrders()->exists()) throw ValidationException::withMessages(['user'=>'Este camarero tiene pedidos asignados.']);
            $locked->delete();
        });
        return redirect()->route('admin.users.index')->with('success','Usuario eliminado.');
    }
    private function preventLastAdmin(): void {
        $adminRole=Role::where('name','Administrator')->firstOrFail();
        // Serializes conflicting admin changes on MySQL using the parent role row.
        Role::whereKey($adminRole->id)->lockForUpdate()->first();
        if(User::where('role_id',$adminRole->id)->count()<=1) throw ValidationException::withMessages(['role_id'=>'Debe permanecer al menos un administrador.']);
    }
}
