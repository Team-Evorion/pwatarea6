<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public const PENDING = 'Pending';
    public const PREPARING = 'Preparing';
    public const READY = 'Ready to serve';
    public const SERVED = 'Served';
    public const STATUSES = [self::PENDING, self::PREPARING, self::READY, self::SERVED];
    protected $fillable = ['user_id','dish_id','quantity','status','order_group','unit_price','waiter_id'];
    protected function casts(): array { return ['unit_price'=>'decimal:2', 'quantity'=>'integer']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function dish(): BelongsTo { return $this->belongsTo(Dish::class); }
    public function waiter(): BelongsTo { return $this->belongsTo(User::class,'waiter_id'); }
    public function getLineTotalAttribute(): float { return (float)$this->unit_price * $this->quantity; }
}
