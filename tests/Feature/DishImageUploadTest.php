<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DishImageUploadTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsAdministrator(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create([
            'username' => 'adminphoto',
            'role_id' => Role::where('name', 'Administrator')->value('id'),
        ]);
        $this->actingAs($user);
    }

    public function test_admin_can_create_dish_with_image_and_replace_it(): void
    {
        Storage::fake('public');
        $this->loginAsAdministrator();

        $this->post(route('dishes.store'), [
            'name' => 'Encebollado',
            'description' => 'Plato de prueba',
            'price' => '5.00',
            'image' => UploadedFile::fake()->image('original.jpg', 800, 600),
        ])->assertRedirect(route('dishes.index'));

        $dish = Dish::where('name', 'Encebollado')->firstOrFail();
        $this->assertNotNull($dish->image_path);
        Storage::disk('public')->assertExists($dish->image_path);
        $originalPath = $dish->image_path;

        $this->put(route('dishes.update', $dish), [
            'name' => 'Encebollado',
            'description' => 'Plato actualizado',
            'price' => '5.50',
            'image' => UploadedFile::fake()->image('nueva.png', 900, 600),
        ])->assertRedirect(route('dishes.index'));

        $dish->refresh();
        Storage::disk('public')->assertExists($dish->image_path);
        Storage::disk('public')->assertMissing($originalPath);
    }

    public function test_photo_is_mandatory_when_creating_dish(): void
    {
        Storage::fake('public');
        $this->loginAsAdministrator();

        $this->post(route('dishes.store'), [
            'name' => 'Tigrillo',
            'price' => '4.00',
        ])->assertSessionHasErrors('image');

        $this->assertDatabaseMissing('dishes', ['name' => 'Tigrillo']);
    }

    public function test_existing_photo_is_retained_when_editing_without_new_upload(): void
    {
        Storage::fake('public');
        $this->loginAsAdministrator();
        $path = UploadedFile::fake()->image('foto.jpg')->store('dishes', 'public');
        $dish = Dish::create(['name' => 'Bolón', 'price' => 3.25, 'image_path' => $path]);

        $this->put(route('dishes.update', $dish), [
            'name' => 'Bolón mixto',
            'price' => '3.75',
        ])->assertRedirect(route('dishes.index'));

        $this->assertSame($path, $dish->fresh()->image_path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_old_dish_without_photo_requires_photo_to_edit(): void
    {
        Storage::fake('public');
        $this->loginAsAdministrator();
        $dish = Dish::create(['name' => 'Plato antiguo', 'price' => 3.25]);

        $this->put(route('dishes.update', $dish), [
            'name' => 'Plato antiguo editado',
            'price' => '3.25',
        ])->assertSessionHasErrors('image');
    }

    public function test_large_image_is_rejected(): void
    {
        Storage::fake('public');
        $this->loginAsAdministrator();

        $this->post(route('dishes.store'), [
            'name' => 'Ceviche',
            'price' => '7.00',
            'image' => UploadedFile::fake()->image('grande.jpg')->size(2500),
        ])->assertSessionHasErrors('image');
    }
}
