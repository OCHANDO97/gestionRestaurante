<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        Permission::create(['name' => 'crear_comanda']);
        Permission::create(['name' => 'ver_facturas']);
        Permission::create(['name' => 'crear_cuentas']);
        Permission::create(['name' => 'ver_usuarios']);
        Permission::create(['name' => 'importar_csv']);
        Permission::create(['name' => 'ver_perfil']);

        // Crear roles
        $admin = Role::create(['name' => 'Administrador']);
        $gerente = Role::create(['name' => 'gerente']);

        $admin->givePermissionTo(Permission::all());
        $gerente->givePermissionTo(['crear_comanda','crear_cuentas','ver_facturas','ver_usuarios']);

        
    }
}
