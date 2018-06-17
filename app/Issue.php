<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;

/** @codeCoverageIgnore */
final class Issue extends Model {

    const TYPE_FULL      = 'issue_lot_full';

    const TYPE_LATE      = 'issue_entry_late';

    const TYPE_NOT_PAYED = 'issue_entry_not_payed';

    protected $dates    = ['created_at', 'updated_at'];

    protected $fillable = ['type'];

    public function lot() {
        return $this->hasOne(ParkingLot::class);
    }

    public function entry() {
        return $this->hasOne(Entry::class);
    }
}