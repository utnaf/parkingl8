<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** @codeCoverageIgnore */
final class ParkingLot extends Model {
    use SoftDeletes;

    const VALIDATION_RULES = [
        'name' => 'required|string|max:191',
        'hourly_fare' => 'required|numeric|min:0',
        'capacity' => 'required|integer|gte:0',
        'threshold_minutes' => 'required|integer|gt:0|lte:30'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function entries() {
        return $this->hasMany(Entry::class);
    }

    public function issues() {
        return $this->hasMany(Issue::class);
    }
}
