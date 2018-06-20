<?php

namespace Parking\Console\Commands;

use Illuminate\Console\Command;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Parking\Service\PriceCalculatorService;

/** @codeCoverageIgnore */
class PayEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulator:entry:pay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate an entry Payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parkingLots = ParkingLot::all();

        $parkingLots->each(function(ParkingLot $lot) {
            $entries = $lot->entries()
                ->whereNull('payed_at')
                ->orderBy('arrived_at')
                ->limit(10)
                ->get();

            if($entries->isEmpty()) {
                return;
            }

            foreach(range(0, random_int(2, 6)) as $key) {
                $entry = $entries->get($key);
                $price = (new PriceCalculatorService)->calculateForEntry($entry);

                if(!$entry instanceof Entry) {
                    continue;
                }

                /** @var EntryRepository $entryRepository */
                $entryRepository = app()->get(EntryRepository::class);

                $entryRepository->updateFields(
                    $entry->id,
                    [
                        'price' => $price
                    ]
                );

                $this->info(
                    sprintf('#%d The car just payed %.2f in the %s lot', $entry->id, $price, $lot->name)
                );
            }
        });
    }
}
