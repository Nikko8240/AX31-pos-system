<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PosSeeder extends Seeder
{
    public function run()
    {
        $createdAt = date('Y-m-d H:i:s');

        $this->db->table('users')->insert([
            'username' => 'admin',
            'full_name' => 'POS Administrator',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'avatar' => null,
            'created_at' => $createdAt,
        ]);

        $this->db->table('products')->insertBatch([
            [
                'name' => 'Notebook',
                'price' => 45.00,
                'stock_quantity' => 30,
                'image' => null,
                'created_at' => $createdAt,
            ],
            [
                'name' => 'Ballpen',
                'price' => 15.00,
                'stock_quantity' => 50,
                'image' => null,
                'created_at' => $createdAt,
            ],
            [
                'name' => 'USB Flash Drive',
                'price' => 350.00,
                'stock_quantity' => 12,
                'image' => null,
                'created_at' => $createdAt,
            ],
        ]);

        $this->db->table('customers')->insertBatch([
            [
                'full_name' => 'Maria Santos',
                'email' => 'maria.santos@example.com',
                'phone' => '09171234567',
                'created_at' => $createdAt,
            ],
            [
                'full_name' => 'Juan Dela Cruz',
                'email' => 'juan.delacruz@example.com',
                'phone' => '09179876543',
                'created_at' => $createdAt,
            ],
        ]);
    }
}