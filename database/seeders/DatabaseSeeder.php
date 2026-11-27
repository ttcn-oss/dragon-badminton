<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ---- Tai khoan demo (mat khau: 12345678) ----
        $users = [
            ['name' => 'Admin',      'email' => 'admin@dragon.test',    'role' => 'admin'],
            ['name' => 'Chu san',    'email' => 'owner@dragon.test',    'role' => 'owner'],
            ['name' => 'Nhan vien',  'email' => 'staff@dragon.test',    'role' => 'staff'],
            ['name' => 'Khach hang', 'email' => 'customer@dragon.test', 'role' => 'customer'],
        ];
        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                $u + [
                    'password' => Hash::make('12345678'),
                    'locale' => 'vi',
                    'is_active' => 1,
                    'email_verified_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $customerId = DB::table('users')->where('email', 'customer@dragon.test')->value('id');
        DB::table('customer_profiles')->updateOrInsert(
            ['phone' => '0900000001'],
            ['user_id' => $customerId, 'full_name' => 'Khach hang demo', 'no_show_count' => 0,
             'created_at' => $now, 'updated_at' => $now]
        );

        // ---- San (San 6 dang bao tri de test trang thai) ----
        $courts = [
            ['San 1', 'San cau long tieu chuan, tham PVC', 'active'],
            ['San 2', 'San cau long tieu chuan, tham PVC', 'active'],
            ['San 3', 'San gan cua ra vao, thoang mat', 'active'],
            ['San 4', 'San co den chong choi', 'active'],
            ['San 5', 'San VIP, co dieu hoa', 'active'],
            ['San 6', 'Dang sua mat san', 'maintenance'],
        ];
        foreach ($courts as [$name, $desc, $status]) {
            DB::table('courts')->updateOrInsert(
                ['name' => $name],
                ['description' => $desc, 'status' => $status,
                 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // ---- Bang gia ----
        if (DB::table('pricing_rules')->count() === 0) {
            $rules = [
                ['weekday', '06:00:00', '17:00:00', 60000],
                ['weekday', '17:00:00', '22:00:00', 100000],
                ['weekend', '06:00:00', '22:00:00', 120000],
                ['holiday', '06:00:00', '22:00:00', 150000],
            ];
            foreach ($rules as [$d, $s, $e, $p]) {
                DB::table('pricing_rules')->insert([
                    'day_type' => $d, 'start_time' => $s, 'end_time' => $e,
                    'price_per_hour' => $p, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // ---- Ngay le ----
        foreach ([['2027-01-01', 'Tet Duong lich'], ['2027-04-30', 'Giai phong mien Nam'], ['2027-09-02', 'Quoc khanh']] as [$d, $n]) {
            DB::table('holidays')->updateOrInsert(['date' => $d], ['name' => $n, 'created_at' => $now, 'updated_at' => $now]);
        }

        // ---- Cai dat (muc 7) ----
        $settings = [
            'open_time' => '06:00', 'close_time' => '22:00', 'slot_minutes' => '30',
            'hold_minutes' => '5', 'hold_extend_max_minutes' => '30',
            'free_cancel_hours' => '24', 'late_cancel_refund_percent' => '0',
            'noshow_grace_minutes' => '15', 'noshow_warn_count' => '2', 'noshow_window_days' => '90',
            'series_min_weeks' => '4', 'series_max_weeks' => '12',
            'series_discount_percent' => '10', 'series_payment_hours' => '24',
        ];
        foreach ($settings as $k => $v) {
            DB::table('settings')->updateOrInsert(['key' => $k], ['value' => $v, 'created_at' => $now, 'updated_at' => $now]);
        }
    }
}
