<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class IssueRepository {

    const LIMIT = 300;

    public function addForLot(ParkingLot $lot, string $type): Issue {
        return $lot->issues()->create([
            'type' => $type
        ]);
    }

    /** @throws NotAcceptableHttpException */
    public function addForEntry(Entry $entry, string $type): Issue {
        if($entry->issueType($type)->count() > 0) {
            throw new NotAcceptableHttpException(
                sprintf('Can\'t add an issue of the same type to entry %d', $entry->id)
            );
        }

        return $entry->issues()->create([
            'type' => $type
        ]);
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id) {
        $issue = Issue::find($id);

        if(!$issue instanceof Issue) {
            throw new NotFoundHttpException(
                sprintf('Can\'t find an issue with ID %d', $id)
            );
        }

        return $issue;
    }

    /** @return Issue[] */
    public function getAll(): Collection {
        return Issue::with(['lot', 'entry'])->where('solved', 0)->get();
    }

    public function openIssueCount(): int {
        return Issue::where('solved', '=', '0')->count();
    }

    public function markIssueAsSolved(Issue $issue): Issue {
        $issue->solved = true;

        Auth::user()->solvedIssues()->save($issue);

        return $issue;
    }
}