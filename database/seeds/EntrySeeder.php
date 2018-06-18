<?php

use Illuminate\Database\Seeder;
use Parking\Entry;

class EntrySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        factory(Entry::class, 500)->create();
    }
}
