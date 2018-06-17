<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class IssueRepository {

    public function addForLot(ParkingLot $lot, string $type): Issue {
        return $lot->issues()->create([
            'type' => $type
        ]);
    }

    public function addForEntry(Entry $entry, string $type): Issue {
        return $entry->issues()->create([
            'type' => $type
        ]);
    }

    /** @throws NotFoundHttpException */
    public function markSolvedByid(int $id) {
        $issue = Issue::find($id);

        if(!$issue instanceof Issue) {
            throw new NotFoundHttpException(
                sprintf('Can\'t find an issue with ID %d', $id)
            );
        }

        // we can avoid to throw a Not Modified exception since is an admin tool :angel:
        $issue->solved = true;

        $issue->save();

        return $issue;
    }

}