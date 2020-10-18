<?php

use App\Brand;
use App\Prodgroup;
use App\Product;
use App\Size;
use App\Sizeprice;
use App\Suppgroup;
use App\Supplier;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = new Product();
        $cat = new Prodgroup();
        $brand = new Brand();
        $size = new Size();
        $suppcat = new Suppgroup();
        $supplier = new Supplier();
        $szpr = new Sizeprice();
        $str = new Str();
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            $name = $faker->text(rand(8, 15)) . rand(111, 999) . '#' . $i;
            $suppcat->create([
                'slug' => $str->slug($name),
                'name' => $name
            ]);
        }

        for ($i = 0; $i < 25; $i++) {
            $name = $faker->name(['male', 'female'][rand(0, 1)]) . rand(111, 999)  . $i;
            $supplier->create([
                'slug' => $str->slug($name),
                'name' => $name,
                'suppgroup_id' => rand(1, 5),
                'company' => $faker->company,
                'address' => $faker->address,
                'contact' => $faker->phoneNumber,
                'balance' => 500 * rand(15, 20),
            ]);
        }



        for ($i = 0; $i < 50; $i++) {
            $name = $faker->text(rand(8, 15)) . rand(111, 999) . '#' . $i;
            $cat->create([
                'slug' => $str->slug($name),
                'name' => $name
            ]);
        }

        for ($i = 0; $i < 50; $i++) {
            $name = $faker->text(rand(8, 15)) . rand(111, 999) . '#' . $i;
            $cat->create([
                'slug' => $str->slug($name),
                'name' => $name
            ]);
        }

        for ($i = 0; $i < 25; $i++) {
            $name = ['10 kg', '25 kg', '50 kg', '100 kg', '6 carton', '12 carton', 'dozen'][$i % 7] . '#' . $i;
            $size->create([
                'slug' => $str->slug($name),
                'name' => $name
            ]);
        }

        for ($i = 0; $i < 35; $i++) {
            $name = ['Cococola', 'Pepis', 'Picfare', 'Rihiam', 'Ice', 'Highland', 'Rwenzori'][$i % 7] . '#' . $i;
            $brand->create([
                'slug' => $str->slug($name),
                'name' => $name
            ]);
        }


        for ($i = 0; $i < 250; $i++) {
            $cost = rand(1, 400) . '000';
            $product =  $products->with('brands', 'sizes', 'sizeprices')->create([
                'name' => $faker->word(),
                'barcode' => rand(11111111111111, 999999999999),
                'category' => $faker->word(),
                'supplier_id' => rand(1, 25),
                'company_name' => $faker->company,
                'cost_price' => $cost,
                'wholesale_price' => $cost + (550 * $i),
                'retailsale_price' => $cost + (750 * $i),
                'quantity' => 15 * $i,
                'tax_percentage' => ($i + 3) % 5,
                'avatar' => '/products/product_avatar_' . ($i % 3) . '.jpg',
                'prodgroup_id' => rand(1, 50),
                'description' => $faker->paragraph(rand(1, 2))
            ]);

            $product->brands()->attach($brand->find([rand(1, 35)]));
            $product->sizes()->attach($size->find([rand(1, 25)]));
            $sizes = $product->sizes()->get();

            foreach ($sizes as $size) {
                $product->sizeprices()->create([
                    'name' => $size->name,
                    'cost' => $cost + (7500 * rand(20, 75)),
                    'whole_sale' => $cost + (7500 * rand(80, 100)),
                    'retail_sale' => $cost + (7500 * rand(150, 220))
                ]);
            }
        }
    }
}
