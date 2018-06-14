<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Entry extends Model {

    /** @var int */
    public $id;

    /** @var Carbon */
    public $arrivedAt;

    /** @var Carbon */
    public $payedAt;

    /** @var Carbon */
    public $exitedAt;

    /**
     * Price are expressed in euros
     *
     * @var float
     */
    public $price;

    /** @inheritdoc */
    public $timestamps = false;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function post() {
        return $this->belongsTo(ParkingLot::class);
    }
}
