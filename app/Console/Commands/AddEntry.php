<?php

namespace Parking\Console\Commands;

use Illuminate\Console\Command;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class AddEntry extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulator:entry:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add an entry to one of the existing Parking Lots';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $parkingLots = ParkingLot::all();

        /** @var ParkingLot $lot */
        $lot = $parkingLots->random();

        /** @var EntryRepository $repository */
        $repository = app()->get(EntryRepository::class);

        try {
            $entry = $repository->addToParkingLot($lot->id);
        } catch (NotAcceptableHttpException $e) {
            $this->warn(
                sprintf('A car attempted to enter a full Parking Lot named %s', $lot->name)
            );
            return;
        }

        $this->info(
            sprintf('#%d A car just entered in the %s lot', $entry->id, $lot->name)
        );
    }
}