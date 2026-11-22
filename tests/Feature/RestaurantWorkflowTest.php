<?php
namespace Tests\Feature;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class RestaurantWorkflowTest extends TestCase {
    use RefreshDatabase;
    protected function setUp(): void { parent::setUp(); $this->seed(RoleSeeder::class); }
    private function userWithRole(string $role): User {
        return User::factory()->create([
            'username'=>'u'.uniqid(),
            'role_id'=>Role::where('name',$role)->value('id'),
        ]);
    }
    public function test_guests_can_view_catalog_but_cannot_manage_dishes(): void {
        $this->get(route('dishes.catalog'))->assertOk();
        $this->get(route('dishes.index'))->assertRedirect(route('login'));
    }
    public function test_register_creates_only_client_account(): void {
        $this->post(route('register.submit'),[
            'name'=>'Ana Torres','username'=>'anatorres','email'=>'ana@example.test',
            'password'=>'MySecretPass88','password_confirmation'=>'MySecretPass88',
            'role_id'=>Role::where('name','Administrator')->value('id'),
        ])->assertRedirect(route('dishes.catalog'));
        $user=User::where('email','ana@example.test')->firstOrFail();
        $this->assertTrue($user->hasRole('Client'));
        $this->assertAuthenticatedAs($user);
    }
    public function test_client_cannot_access_admin_pages(): void {
        $this->actingAs($this->userWithRole('Client'));
        $this->get(route('admin.dashboard'))->assertForbidden();
        $this->get(route('dishes.create'))->assertForbidden();
        $this->get(route('kitchen.index'))->assertForbidden();
    }
    public function test_admin_can_manage_dishes_and_users(): void {
        Storage::fake('public');
        $admin=$this->userWithRole('Administrator');
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->post(route('dishes.store'),[
            'name'=>'Encebollado',
            'description'=>'Tradicional',
            'price'=>5.25,
            'image'=>UploadedFile::fake()->image('plato.jpg', 800, 600),
        ])->assertRedirect(route('dishes.index'));
        $this->assertDatabaseHas('dishes',['name'=>'Encebollado']);
        $this->get(route('admin.users.index'))->assertOk();
    }
    public function test_order_goes_from_client_to_chef_to_waiter(): void {
        $client=$this->userWithRole('Client'); $chef=$this->userWithRole('Chef'); $waiter=$this->userWithRole('Waiter');
        $dish=Dish::create(['name'=>'Bolón','description'=>'De queso','price'=>4.25]);
        $this->actingAs($client)->post(route('orders.store'),['items_json'=>json_encode([['dish_id'=>$dish->id,'quantity'=>2]])])->assertRedirect();
        $order=Order::firstOrFail();
        $this->assertSame(Order::PENDING,$order->status); $this->assertEquals(8.50,$order->line_total);
        $this->actingAs($chef)->patch(route('kitchen.transition',['order'=>$order,'action'=>'start']))->assertRedirect();
        $this->assertSame(Order::PREPARING,$order->fresh()->status);
        $this->patch(route('kitchen.transition',['order'=>$order,'action'=>'ready']))->assertRedirect();
        $this->assertSame(Order::READY,$order->fresh()->status);
        $this->actingAs($waiter)->patch(route('waiter.serve',$order))->assertRedirect();
        $this->assertSame(Order::SERVED,$order->fresh()->status);
        $this->actingAs($client)->get(route('orders.index'))->assertOk();
    }
    public function test_client_cannot_view_other_clients_order(): void {
        $first=$this->userWithRole('Client'); $other=$this->userWithRole('Client');
        $dish=Dish::create(['name'=>'Ceviche','price'=>7.00]);
        $this->actingAs($first)->post(route('orders.store'),['items_json'=>json_encode([['dish_id'=>$dish->id,'quantity'=>1]])]);
        $group=Order::firstOrFail()->order_group;
        $this->actingAs($other)->get(route('orders.show',['group'=>$group]))->assertNotFound();
    }
    public function test_invalid_state_transition_is_rejected(): void {
        $client=$this->userWithRole('Client'); $chef=$this->userWithRole('Chef');
        $dish=Dish::create(['name'=>'Tigrillo','price'=>3.50]);
        $this->actingAs($client)->post(route('orders.store'),['items_json'=>json_encode([['dish_id'=>$dish->id,'quantity'=>1]])]);
        $order=Order::firstOrFail();
        $this->actingAs($chef)->patch(route('kitchen.transition',['order'=>$order,'action'=>'ready']))->assertSessionHasErrors('status');
        $this->assertSame(Order::PENDING,$order->fresh()->status);
    }
}
