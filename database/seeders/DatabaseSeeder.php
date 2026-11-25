<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tai khoan quan tri (doi mat khau bang bien moi truong ADMIN_PASSWORD tren host)
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@dragon.test')],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123')),
                'role' => 'admin',
            ]
        );

        // San mau (chi tao neu chua co)
        $courts = [
            ['Sân 1', 'Sân thảm PVC, có mái che', 80000],
            ['Sân 2', 'Gần cửa ra vào, ánh sáng tốt', 80000],
            ['Sân 3', 'Sân thảm PVC, đèn LED mới', 90000],
            ['Sân VIP', 'Có điều hòa, ghế nghỉ và tủ khóa', 150000],
        ];
        foreach ($courts as [$name, $desc, $price]) {
            Court::firstOrCreate(
                ['name' => $name],
                ['description' => $desc, 'status' => 'active', 'price_per_hour' => $price]
            );
        }
    }
}
