<?php
declare(strict_types=1);

namespace Parking\Events\Models;

use Illuminate\Support\Carbon;
use Parking\Entry;

final class EntryObserver {

    public function updating(Entry $instance) {
        if (
            $instance->getOriginal('price') === null
            && $instance->getAttribute('price') !== null
        ) {
            $instance->payed_at = new Carbon();
        }
    }
}