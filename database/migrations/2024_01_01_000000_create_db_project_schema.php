<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS db_project');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.products (
            id SERIAL PRIMARY KEY,
            product_type VARCHAR(50) NOT NULL CHECK (product_type IN (\'component\', \'peripheral\', \'software\')),
            name VARCHAR(255) NOT NULL,
            company VARCHAR(255) NOT NULL,
            price INTEGER,
            description VARCHAR(500)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.components (
            product_id INTEGER PRIMARY KEY REFERENCES db_project.products(id) ON DELETE CASCADE,
            vendor VARCHAR(255),
            category VARCHAR(100) NOT NULL
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.peripherals (
            product_id INTEGER PRIMARY KEY REFERENCES db_project.products(id) ON DELETE CASCADE,
            category VARCHAR(100) NOT NULL,
            connection_type VARCHAR(100) NOT NULL
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.software (
            product_id INTEGER PRIMARY KEY REFERENCES db_project.products(id) ON DELETE CASCADE,
            license_duration INTERVAL
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.users (
            id SERIAL PRIMARY KEY,
            surname VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255),
            birth_date DATE NOT NULL,
            region VARCHAR(100) NOT NULL
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.users_auth_data (
            user_id INTEGER PRIMARY KEY REFERENCES db_project.users(id) ON DELETE CASCADE,
            login VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT \'user\' CHECK (role IN (\'user\', \'admin\'))
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.users_contacts (
            user_id INTEGER NOT NULL REFERENCES db_project.users(id) ON DELETE CASCADE,
            phone_number VARCHAR(15) NOT NULL,
            PRIMARY KEY (user_id, phone_number)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.warehouses (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            region VARCHAR(100) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL DEFAULT \'5999-12-31\'
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.warehouse_inventory (
            warehouse_id INTEGER NOT NULL REFERENCES db_project.warehouses(id) ON DELETE CASCADE,
            product_id INTEGER NOT NULL REFERENCES db_project.products(id) ON DELETE CASCADE,
            quantity INTEGER NOT NULL,
            PRIMARY KEY (warehouse_id, product_id)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.deliveries (
            product_id INTEGER NOT NULL REFERENCES db_project.products(id) ON DELETE CASCADE,
            warehouse_id INTEGER NOT NULL REFERENCES db_project.warehouses(id) ON DELETE CASCADE,
            delivery_date DATE NOT NULL,
            quantity INTEGER NOT NULL,
            PRIMARY KEY (product_id, warehouse_id, delivery_date)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.orders (
            user_id INTEGER NOT NULL REFERENCES db_project.users(id) ON DELETE CASCADE,
            created_at TIMESTAMP NOT NULL,
            valid_until TIMESTAMP DEFAULT \'5999-12-31 23:59:59\',
            order_status VARCHAR(50) DEFAULT \'selected\' CHECK (order_status IN (\'selected\',\'collecting\', \'underway\',\'delivered\',\'recieved\',\'rejected\',\'returned\')),
            PRIMARY KEY (user_id, created_at)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.order_lists (
            user_id INTEGER NOT NULL,
            created_at TIMESTAMP NOT NULL,
            product_id INTEGER NOT NULL REFERENCES db_project.products(id) ON DELETE CASCADE,
            quantity INTEGER NOT NULL,
            PRIMARY KEY (user_id, created_at, product_id),
            FOREIGN KEY (user_id, created_at) REFERENCES db_project.orders(user_id, created_at) ON DELETE CASCADE
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.orders_history (
            user_id INTEGER NOT NULL,
            created_at TIMESTAMP NOT NULL,
            valid_until TIMESTAMP DEFAULT \'5999-12-31 23:59:59\',
            order_status VARCHAR(50) NOT NULL,
            update_date TIMESTAMP NOT NULL,
            PRIMARY KEY (user_id, created_at, update_date),
            FOREIGN KEY (user_id) REFERENCES db_project.users(id)
        )');

        DB::statement('CREATE TABLE IF NOT EXISTS db_project.order_lists_history (
            user_id INTEGER NOT NULL,
            created_at TIMESTAMP NOT NULL,
            product_id INTEGER NOT NULL REFERENCES db_project.products(id) ON DELETE CASCADE,
            quantity INTEGER NOT NULL,
            update_date TIMESTAMP NOT NULL,
            PRIMARY KEY (user_id, created_at, product_id, update_date),
            FOREIGN KEY (user_id, created_at) REFERENCES db_project.orders(user_id, created_at) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES db_project.products(id)
        )');
        DB::unprepared('
            CREATE OR REPLACE PROCEDURE db_project.process_pending_deliveries()
            LANGUAGE plpgsql
            AS $$
            BEGIN
                UPDATE db_project.deliveries 
                SET delivery_date = delivery_date
                WHERE delivery_date = CURRENT_DATE
                AND EXISTS (
                    SELECT 1 
                    FROM db_project.warehouses w 
                    WHERE w.id = warehouse_id
                    AND w.start_date <= CURRENT_DATE
                    AND w.end_date > CURRENT_DATE
                );
            END;
            $$;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS db_project CASCADE');
    }
};