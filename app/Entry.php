<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model {

    /** @inheritdoc */
    public $timestamps = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function post() {
        return $this->belongsTo(ParkingLot::class);
    }
}
