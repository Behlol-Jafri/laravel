<?php

namespace Database\Seeders;

use App\Models\AccessGrant;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────────────────────────────────
        $superAdmin = User::create([
            'name'      => 'Super Admin',
            'email'     => 'superadmin@demo.com',
            'phone'     => '+92 300 0000001',
            'role'      => 'super_admin',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        // ── Admins ───────────────────────────────────────────────────────────────
        $admin1 = User::create([
            'name'      => 'Ahmed Admin',
            'email'     => 'admin@demo.com',
            'phone'     => '+92 301 1111111',
            'role'      => 'admin',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $admin2 = User::create([
            'name'      => 'Sara Admin',
            'email'     => 'sara.admin@demo.com',
            'phone'     => '+92 302 2222222',
            'role'      => 'admin',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        // ── Vendors ──────────────────────────────────────────────────────────────
        $vendor1 = User::create([
            'name'      => 'Tech Vendor',
            'email'     => 'vendor@demo.com',
            'phone'     => '+92 303 3333333',
            'role'      => 'vendor',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $vendor2 = User::create([
            'name'      => 'Fashion Store',
            'email'     => 'fashion@demo.com',
            'phone'     => '+92 304 4444444',
            'role'      => 'vendor',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $vendor3 = User::create([
            'name'      => 'Foods & Groceries',
            'email'     => 'foods@demo.com',
            'phone'     => '+92 305 5555555',
            'role'      => 'vendor',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        // ── Users ─────────────────────────────────────────────────────────────────
        $user1 = User::create([
            'name'      => 'Ali User',
            'email'     => 'user@demo.com',
            'phone'     => '+92 306 6666666',
            'role'      => 'user',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $user2 = User::create([
            'name'      => 'Zara Customer',
            'email'     => 'zara@demo.com',
            'phone'     => '+92 307 7777777',
            'role'      => 'user',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $user3 = User::create([
            'name'      => 'Bilal Shopper',
            'email'     => 'bilal@demo.com',
            'phone'     => '+92 308 8888888',
            'role'      => 'user',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        // ── Access Grants: Super Admin → Admins ──────────────────────────────────
        AccessGrant::create([
            'granter_id'   => $superAdmin->id,
            'grantee_id'   => $admin1->id,
            'access_level' => 'full',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $superAdmin->id,
            'grantee_id'   => $admin2->id,
            'access_level' => 'read',
            'is_active'    => true,
        ]);

        // Super Admin grants Admin1 access to Vendor1 & Vendor2
        AccessGrant::create([
            'granter_id'   => $superAdmin->id,
            'grantee_id'   => $vendor1->id,
            'access_level' => 'full',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $superAdmin->id,
            'grantee_id'   => $vendor2->id,
            'access_level' => 'read',
            'is_active'    => true,
        ]);

        // ── Access Grants: Admin1 → Vendors ──────────────────────────────────────
        AccessGrant::create([
            'granter_id'   => $admin1->id,
            'grantee_id'   => $vendor1->id,
            'access_level' => 'full',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $admin1->id,
            'grantee_id'   => $vendor2->id,
            'access_level' => 'read',
            'is_active'    => true,
        ]);

        // ── Access Grants: Vendor1 → Users ────────────────────────────────────────
        AccessGrant::create([
            'granter_id'   => $vendor1->id,
            'grantee_id'   => $user1->id,
            'access_level' => 'full',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $vendor1->id,
            'grantee_id'   => $user2->id,
            'access_level' => 'read',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $vendor2->id,
            'grantee_id'   => $user2->id,
            'access_level' => 'read',
            'is_active'    => true,
        ]);

        AccessGrant::create([
            'granter_id'   => $vendor2->id,
            'grantee_id'   => $user3->id,
            'access_level' => 'full',
            'is_active'    => true,
        ]);

        // ── Products ─────────────────────────────────────────────────────────────
        $products = [
            // Tech Vendor
            ['vendor_id' => $vendor1->id, 'name' => 'Samsung Galaxy S24', 'description' => 'Latest Samsung flagship smartphone', 'price' => 150000, 'stock' => 25, 'category' => 'Electronics'],
            ['vendor_id' => $vendor1->id, 'name' => 'Dell Laptop Core i7', 'description' => '16GB RAM, 512GB SSD laptop', 'price' => 220000, 'stock' => 10, 'category' => 'Electronics'],
            ['vendor_id' => $vendor1->id, 'name' => 'AirPods Pro', 'description' => 'Noise-cancelling wireless earbuds', 'price' => 45000, 'stock' => 50, 'category' => 'Electronics'],
            ['vendor_id' => $vendor1->id, 'name' => 'iPad Air', 'description' => '10.9-inch Retina display tablet', 'price' => 120000, 'stock' => 15, 'category' => 'Electronics'],
            // Fashion Store
            ['vendor_id' => $vendor2->id, 'name' => 'Men\'s Shalwar Kameez', 'description' => 'Premium cotton shalwar kameez', 'price' => 3500, 'stock' => 100, 'category' => 'Clothing'],
            ['vendor_id' => $vendor2->id, 'name' => 'Women\'s Lawn Suit', 'description' => '3-piece unstitched lawn suit', 'price' => 5500, 'stock' => 80, 'category' => 'Clothing'],
            ['vendor_id' => $vendor2->id, 'name' => 'Leather Shoes', 'description' => 'Genuine leather formal shoes', 'price' => 12000, 'stock' => 30, 'category' => 'Footwear'],
            // Foods
            ['vendor_id' => $vendor3->id, 'name' => 'Basmati Rice 5kg', 'description' => 'Premium aged basmati rice', 'price' => 1800, 'stock' => 200, 'category' => 'Grocery'],
            ['vendor_id' => $vendor3->id, 'name' => 'Mixed Dry Fruits 1kg', 'description' => 'Assorted premium dry fruits', 'price' => 3200, 'stock' => 60, 'category' => 'Grocery'],
        ];

        $createdProducts = [];
        foreach ($products as $pd) {
            $createdProducts[] = Product::create(array_merge($pd, ['is_active' => true]));
        }

        // ── Sample Orders ────────────────────────────────────────────────────────
        $sampleOrders = [
            ['user_id' => $user1->id, 'product_id' => $createdProducts[0]->id, 'quantity' => 1, 'total_price' => 150000, 'status' => 'completed'],
            ['user_id' => $user1->id, 'product_id' => $createdProducts[2]->id, 'quantity' => 2, 'total_price' => 90000,  'status' => 'processing'],
            ['user_id' => $user2->id, 'product_id' => $createdProducts[1]->id, 'quantity' => 1, 'total_price' => 220000, 'status' => 'pending'],
            ['user_id' => $user2->id, 'product_id' => $createdProducts[4]->id, 'quantity' => 2, 'total_price' => 7000,   'status' => 'completed'],
            ['user_id' => $user3->id, 'product_id' => $createdProducts[5]->id, 'quantity' => 3, 'total_price' => 16500,  'status' => 'pending'],
            ['user_id' => $user1->id, 'product_id' => $createdProducts[7]->id, 'quantity' => 2, 'total_price' => 3600,   'status' => 'completed'],
        ];

        foreach ($sampleOrders as $order) {
            Order::create($order);
        }

        $this->command->info('✅ Demo data seeded successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'superadmin@demo.com', 'password'],
                ['Admin',       'admin@demo.com',       'password'],
                ['Vendor',      'vendor@demo.com',       'password'],
                ['User',        'user@demo.com',         'password'],
            ]
        );
    }
}
