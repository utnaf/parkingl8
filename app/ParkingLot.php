<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkingLot extends Model
{
    use SoftDeletes;

    /** @var int */
    public $id;

    /** @var string */
    public $name;

    /**
     * The fare is expressed in euros.
     * @var float
     */
    public $hourlyFare;

    protected $dates = ['deleted_at','created_at','updated_at'];
}
