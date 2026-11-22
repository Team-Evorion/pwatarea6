<?php
namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Seeder;
class RoleSeeder extends Seeder {
    public function run(): void {
        foreach(['Administrator','Chef','Waiter','Client'] as $name){ Role::firstOrCreate(['name'=>$name]); }
    }
}
