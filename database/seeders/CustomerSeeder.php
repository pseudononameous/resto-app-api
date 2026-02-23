<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['customer_code' => 'CUS001', 'first_name' => 'Maria', 'last_name' => 'Santos', 'phone' => '+639171001001', 'email' => 'maria@example.com', 'marketing_opt_in' => true],
            ['customer_code' => 'CUS002', 'first_name' => 'Juan', 'last_name' => 'Dela Cruz', 'phone' => '+639171001002', 'email' => 'juan@example.com', 'marketing_opt_in' => true],
            ['customer_code' => 'CUS003', 'first_name' => 'Ana', 'last_name' => 'Reyes', 'phone' => '+639171001003', 'email' => 'ana@example.com', 'marketing_opt_in' => false],
            ['customer_code' => 'CUS004', 'first_name' => 'Pedro', 'last_name' => 'Garcia', 'phone' => '+639171001004', 'email' => 'pedro@example.com', 'marketing_opt_in' => true],
            ['customer_code' => 'CUS005', 'first_name' => 'Sofia', 'last_name' => 'Lopez', 'phone' => '+639171001005', 'email' => 'sofia@example.com', 'marketing_opt_in' => true],
        ];
        foreach ($rows as $data) {
            Customer::firstOrCreate(['customer_code' => $data['customer_code']], $data);
        }
    }
}
