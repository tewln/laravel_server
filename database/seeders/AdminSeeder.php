<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserAuthData;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminAuth = \App\Models\UserAuthData::where('login', 'admin')->first();
        if ($adminAuth) {
            $adminAuth->password = Hash::make('admin');
            $adminAuth->role = 'admin';
            $adminAuth->save();
            echo "Админ обновлён: login = admin, password = admin\n";
            return;
        }
        
        $maxId = \App\Models\User::max('id') ?? 0;
        $admin = User::create([
            'id' => $maxId + 1,
            'surname' => 'Админов',
            'firstname' => 'Админ',
            'lastname' => 'Админович',
            'birth_date' => '1980-01-01',
            'region' => 'Москва',
        ]);
        UserAuthData::create([
            'user_id' => $admin->id,
            'login' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);
        echo "Админ создан: login = admin, password = admin\n";
    }
}