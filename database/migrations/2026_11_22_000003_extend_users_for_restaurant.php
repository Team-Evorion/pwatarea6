<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        // Existing Laravel users must remain valid; populate them as Clients.
        DB::table('roles')->updateOrInsert(['name'=>'Client'],['name'=>'Client']);
        $clientId = DB::table('roles')->where('name','Client')->value('id');
        Schema::table('users', function(Blueprint $table){
            $table->string('username',50)->nullable()->unique();
            $table->foreignId('role_id')->nullable()->constrained('roles')->restrictOnDelete();
        });
        DB::table('users')->whereNull('role_id')->update(['role_id'=>$clientId]);
        // Use generated username values for existing users without colliding.
        DB::table('users')->whereNull('username')->orderBy('id')->chunkById(100, function($users){
            foreach($users as $user){ DB::table('users')->where('id',$user->id)->update(['username'=>'user'.$user->id]); }
        });
    }
    public function down(): void {
        Schema::table('users', function(Blueprint $table){
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('username');
        });
    }
};
