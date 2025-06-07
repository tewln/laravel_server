<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('db_project.products')->insert([
            [
                'product_type' => 'component',
                'name' => 'Ryzen 5 5600X',
                'company' => 'AMD',
                'price' => 15000,
                'description' => '6-core CPU',
            ],
            [
                'product_type' => 'component',
                'name' => 'Core i5-12400F',
                'company' => 'Intel',
                'price' => 17000,
                'description' => '6-core CPU',
            ],
            [
                'product_type' => 'peripheral',
                'name' => 'Logitech G102',
                'company' => 'Logitech',
                'price' => 2000,
                'description' => 'Gaming mouse',
            ],
            [
                'product_type' => 'software',
                'name' => 'Windows 11 Home',
                'company' => 'Microsoft',
                'price' => 9000,
                'description' => 'OS',
            ],
            [
                'product_type' => 'component',
                'name' => 'GeForce RTX 4060',
                'company' => 'NVIDIA',
                'price' => 35000,
                'description' => 'Video card',
            ],
        ]);
        DB::table('db_project.components')->insert([
            [
                'product_id' => 1,
                'vendor' => 'AMD',
                'category' => 'CPU'
            ],
            [
                'product_id' => 2,
                'vendor' => 'Intel',
                'category' => 'CPU'
            ],
            [
                'product_id' => 5,
                'vendor' => 'NVIDIA',
                'category' => 'GPU'
            ],
        ]);
        DB::table('db_project.peripherals')->insert([
            [
                'product_id' => 3,
                'category' => 'mouse',
                'connection_type' => 'USB'
            ],
        ]);
        DB::table('db_project.software')->insert([
            [
                'product_id' => 4,
                'license_duration' => '365 days'
            ],
        ]);
        DB::table('db_project.warehouses')->insert([
            [
                'name' => 'Склад Москва',
                'region' => 'Москва',
                'start_date' => '2020-01-01',
                'end_date' => '5999-12-31',
            ],
            [
                'name' => 'Склад СПб',
                'region' => 'Санкт-Петербург',
                'start_date' => '2021-01-01',
                'end_date' => '5999-12-31',
            ],
        ]);
        DB::table('db_project.warehouse_inventory')->insert([
            [
                'warehouse_id' => 1,
                'product_id' => 1,
                'quantity' => 10
            ],
            [
                'warehouse_id' => 1,
                'product_id' => 3,
                'quantity' => 25
            ],
            [
                'warehouse_id' => 2,
                'product_id' => 2,
                'quantity' => 15
            ],
            [
                'warehouse_id' => 2,
                'product_id' => 4,
                'quantity' => 5
            ],
        ]);
        DB::table('db_project.deliveries')->insert([
            [
                'product_id' => 1,
                'warehouse_id' => 1,
                'delivery_date' => '2024-01-10',
                'quantity' => 10
            ],
            [
                'product_id' => 3,
                'warehouse_id' => 1,
                'delivery_date' => '2024-01-15',
                'quantity' => 25
            ],
            [
                'product_id' => 2,
                'warehouse_id' => 2,
                'delivery_date' => '2024-01-20',
                'quantity' => 15
            ],
            [
                'product_id' => 4,
                'warehouse_id' => 2,
                'delivery_date' => '2024-01-25',
                'quantity' => 5
            ],
        ]);
        DB::table('db_project.users')->insert([
            [
                'surname' => 'Иванов',
                'firstname' => 'Иван',
                'lastname' => 'Иванович',
                'birth_date' => '1990-01-01',
                'region' => 'Москва',
            ],
            [
                'surname' => 'Петров',
                'firstname' => 'Петр',
                'lastname' => 'Петрович',
                'birth_date' => '1985-05-05',
                'region' => 'Санкт-Петербург',
            ],
        ]);
        DB::table('db_project.users_auth_data')->insert([
            [
                'user_id' => 1,
                'login' => 'user',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'user_id' => 2,
                'login' => 'petr',
                'password' => Hash::make('petrpass'),
                'role' => 'admin',
            ],
        ]);
        DB::table('db_project.users_contacts')->insert([
            [
                'user_id' => 1,
                'phone_number' => '+79991234567'
            ],
            [
                'user_id' => 2,
                'phone_number' => '+79997654321'
            ],
        ]);
        DB::table('db_project.orders')->insert([
            [
                'user_id' => 1,
                'created_at' => '2024-02-01 10:00:00',
                'valid_until' => '2024-03-01 10:00:00',
                'order_status' => 'selected',
            ],
            [
                'user_id' => 2,
                'created_at' => '2024-02-02 11:00:00',
                'valid_until' => '2024-03-02 11:00:00',
                'order_status' => 'delivered',
            ],
        ]);
        DB::table('db_project.order_lists')->insert([
            [
                'user_id' => 1,
                'created_at' => '2024-02-01 10:00:00',
                'product_id' => 1,
                'quantity' => 1
            ],
            [
                'user_id' => 1,
                'created_at' => '2024-02-01 10:00:00',
                'product_id' => 3,
                'quantity' => 2
            ],
            [ 
                'user_id' => 2,
                'created_at' => '2024-02-02 11:00:00',
                'product_id' => 2,
                'quantity' => 1
            ],
            [
                'user_id' => 2,
                'created_at' => '2024-02-02 11:00:00',
                'product_id' => 4,
                'quantity' => 1
            ],
        ]);
        $this->call(AdminSeeder::class);
    }
}
