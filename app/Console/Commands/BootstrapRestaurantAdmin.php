<?php
namespace App\Console\Commands;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class BootstrapRestaurantAdmin extends Command {
    protected $signature = 'restaurant:create-admin {email} {--name=Administrador}';
    protected $description = 'Crear la cuenta administradora inicial con contraseña solicitada de forma privada';
    public function handle(): int {
        $email=(string)$this->argument('email'); $name=(string)$this->option('name');
        if(User::where('email',$email)->exists()){ $this->error('Ya existe ese correo.'); return self::FAILURE; }
        $pass=$this->secret('Contraseña (min. 10 caracteres)');
        $confirmation=$this->secret('Confirma la contraseña');
        $errors=Validator::make(['email'=>$email,'name'=>$name,'password'=>$pass],[
           'email'=>'required|email|max:100', 'name'=>'required|string|max:100', 'password'=>'required|string|min:10',
        ]);
        if($errors->fails() || $pass!==$confirmation){ $this->error('Datos inválidos o contraseñas diferentes.'); return self::FAILURE; }
        $role=Role::firstOrCreate(['name'=>'Administrator']);
        User::create(['name'=>$name,'username'=>'admin'.Str::lower(Str::random(10)),'email'=>$email,'password'=>Hash::make($pass),'role_id'=>$role->id]);
        $this->info('Administrador creado correctamente.');
        return self::SUCCESS;
    }
}
