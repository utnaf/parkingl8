<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ParkingLot extends Model {
    use SoftDeletes;

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /**
     * The fare is expressed in euros.
     *
     * @var float
     */
    public $hourlyFare;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    public function entries() {
        return $this->hasMany(Entry::class);
    }
}
