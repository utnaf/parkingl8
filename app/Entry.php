<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;

/** @codeCoverageIgnore */
class Entry extends Model {

    /** @inheritdoc */
    public $timestamps = false;

    protected $dates = ['arrived_at', 'exited_at', 'payed_at'];

    public function parkingLot() {
        return $this->belongsTo(ParkingLot::class);
    }

    public function issues() {
        return $this->hasMany(Issue::class);
    }
}
