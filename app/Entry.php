<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** @codeCoverageIgnore */
class Entry extends Model {

    /** @inheritdoc */
    public $timestamps = false;

    protected $dates      = ['arrived_at', 'exited_at', 'payed_at'];

    protected $fillable   = ['arrived_at'];

    public function parkingLot() {
        return $this->belongsTo(ParkingLot::class);
    }

    public function scopeIssueType(Builder $query, string $type): Builder {
        return $query->whereHas('issues', function(Builder $query) use($type) {
           $query->where('type', $type);
        });
    }

    public function issues() {
        return $this->hasMany(Issue::class);
    }
}
