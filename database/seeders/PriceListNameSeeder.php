<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceListNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priceListNames = [
            [
                'name' => 'direct',
                'description' => 'Prix direct au consommateur, sans intermédiaire.',
            ],
            [
                'name' => 'demi-gros',
                'description' => 'Prix pour la vente en demi-gros, intermédiaire entre le détail et le gros.',
            ],
            [
                'name' => 'grossite',
                'description' => 'Prix pour la vente en gros, généralement destiné aux revendeurs.',
            ],
            [
                'name' => 'mensuel',
                'description' => 'Prix mensuel basé sur des abonnements ou des engagements de longue durée.',
            ],
        ];

        DB::table('price_list_names')->insert($priceListNames);
    }
}
