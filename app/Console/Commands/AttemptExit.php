<?php

namespace Parking\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Parking\ParkingLot;
use Parking\Repositories\EntryRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** @codeCoverageIgnore */
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

        $parkingLots->each(function(ParkingLot $lot) {
            $entries = $lot->entries()
                ->whereNull('exited_at')
                ->whereNotNull('payed_at')
                ->orderBy('payed_at', 'asc')
                ->orderBy('arrived_at')
                ->limit(40)
                ->get();

            if($entries->isEmpty()) {
                return;
            }

            foreach(range(0, random_int(0, 1)) as $key) {
                $entry = $entries->get($key);

                /** @var EntryRepository $entryRepository */
                $entryRepository = app()->get(EntryRepository::class);

                $now = Carbon::now();

                $this->info(
                    sprintf(
                        '#%d That entered at %s and payed at %s is trying to exit from %s at %s',
                        $entry->id,
                        $entry->arrived_at->format('d/m/Y H:i'),
                        ($entry->payed_at ? $entry->payed_at->format('d/m/Y H:i') : 'NEVER'),
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
        });
    }
}
