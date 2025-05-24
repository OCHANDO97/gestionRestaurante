<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
class AsigneRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'id_Empleado' => 39599,
            'password' => Hash::make(12345678),
        ]);
    
        event(new Registered($user));
        
        $user = User::find(1); 
        $user->assignRole('Administrador');
    }
}
