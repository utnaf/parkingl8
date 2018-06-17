<?php

namespace Parking\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AttemptExit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulator:entry:exit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempt to exit from a Parking Lot';

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

        foreach(range(0, random_int(3, 6)) as $_times) {
            /** @var ParkingLot $lot */
            $lot = $parkingLots->random();

            $entries = $lot->entries()
                ->whereNull('exited_at')
                ->whereNotNull('payed_at')
                ->orderBy('payed_at')
                ->orderBy('arrived_at')
                ->limit(20)
                ->get();

            if($entries->isEmpty()) {
                continue;
            }

            $entry = $entries->random();

            /** @var EntryRepository $entryRepository */
            $entryRepository = app()->get(EntryRepository::class);

            $now = Carbon::now();

            $this->info(
                sprintf(
                    '#%d That entered at %s and payed at %s is trying to exit from %s at %s',
                    $entry->id,
                    $entry->arrived_at->format('d/m/Y H:i'),
                    $entry->payed_at->format('d/m/Y H:i'),
                    $lot->name,
                    $now->format('d/m/Y H:i')
                )
            );

            try {
                $entryRepository->updateFields(
                    $entry->id,
                    [
                        'exited_at' => $now
                    ]
                );
            } catch (HttpException $e) {
                $this->warn(
                    sprintf('#%d Can\'t exit from %s.', $entry->id, $lot->name)
                );
                continue;
            }

            $this->info(
                sprintf('#%d Exited from %s', $entry->id, $lot->name)
            );
        }
    }
}