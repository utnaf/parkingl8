<?php
declare(strict_types=1);

namespace Parking;

use Illuminate\Database\Eloquent\Model;

/** @codeCoverageIgnore */
final class Issue extends Model {

    const TYPE_LATE      = 'issue_entry_late';

    const TYPE_NOT_PAYED = 'issue_entry_not_payed';

    protected $dates    = ['created_at', 'updated_at'];

    protected $fillable = ['type'];

    protected $hidden = ['created_at', 'updated_at'];

    public function lot() {
        return $this->belongsTo(ParkingLot::class);
    }

    public function entry() {
        return $this->belongsTo(Entry::class);
    }

    public function completedBy() {
        return $this->belongsTo(User::class, 'completed_by');
    }
}