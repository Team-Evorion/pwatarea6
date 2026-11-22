<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'username', 'email', 'password', 'role_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array { return ['email_verified_at'=>'datetime', 'password'=>'hashed']; }
    public function role(): BelongsTo { return $this->belongsTo(Role::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function assignedOrders(): HasMany { return $this->hasMany(Order::class, 'waiter_id'); }
    public function hasRole(string ...$roles): bool { return in_array($this->role?->name, $roles, true); }
}
