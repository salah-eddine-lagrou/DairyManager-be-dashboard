<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'Administrateur avec un accès complet', 'prefix' => 'AD'],
            ['role_name' => 'vendeur', 'description' => 'Représentant commercial', 'prefix' => 'VD'],
            ['role_name' => 'responsable', 'description' => 'Personne responsable', 'prefix' => 'RS'],
            ['role_name' => 'magasinier', 'description' => 'Gestionnaire d\'entrepôt', 'prefix' => 'MG'],
            // Add other initial roles here
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['role_name' => $role['role_name']], $role);
        }
    }
}
