<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $sql = file_get_contents(database_path('seeders/transactions.sql'));
        DB::unprepared($sql);
    }
}
