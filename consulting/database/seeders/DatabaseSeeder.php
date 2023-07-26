<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expert;
use App\Models\Consultation;
use Illuminate\Database\Seeder;
use App\Models\Consultation_type;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // consults_types
        DB::table('consults_types')->insert([
            'type' => 'Medical'
        ]);
        DB::table('consults_types')->insert([
            'type' => 'Professional'
        ]);
        DB::table('consults_types')->insert([
            'type' => 'Psychological'
        ]);
        DB::table('consults_types')->insert([
            'type' => 'Familial'
        ]);
        DB::table('consults_types')->insert([
            'type' => 'Buisiness'
        ]);
    }
}
