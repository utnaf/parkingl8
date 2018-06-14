<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    protected $toTruncate = [
        'entries',
        'parking_lots',
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->truncateTables();

        $this->call(ParkingLotSeeder::class);
        $this->call(EntrySeeder::class);
    }

    private function truncateTables() {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
