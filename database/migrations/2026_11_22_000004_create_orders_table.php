<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('dish_id')->constrained('dishes')->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->enum('status',['Pending','Preparing','Ready to serve','Served'])->default('Pending');
            $table->uuid('order_group')->index(); // Links multiple dishes in one checkout
            $table->decimal('unit_price',10,2); // Snapshot; never trust client-side price
            $table->foreignId('waiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['user_id','created_at']);
            $table->index(['status','created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
