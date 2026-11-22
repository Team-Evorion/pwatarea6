<?php
namespace Database\Seeders;
use App\Models\Dish;
use Illuminate\Database\Seeder;
class DemoDishSeeder extends Seeder {
    public function run(): void {
        foreach([
            ['name'=>'Encebollado','description'=>'Caldo tradicional de pescado, yuca y cebolla encurtida','price'=>5.50],
            ['name'=>'Bolón de verde','description'=>'Verde majado con queso y chicharrón','price'=>3.75],
            ['name'=>'Arroz con menestra','description'=>'Arroz, menestra y carne a la parrilla','price'=>6.90],
            ['name'=>'Ceviche de camarón','description'=>'Camarones en jugo cítrico y vegetales','price'=>7.20],
        ] as $dish){ Dish::firstOrCreate(['name'=>$dish['name']],$dish); }
    }
}
