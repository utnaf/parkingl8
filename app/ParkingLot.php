<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ParkingLot extends Model {
    use SoftDeletes;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function entries() {
        return $this->hasMany(Entry::class);
    }
}
