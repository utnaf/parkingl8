<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;
use Parking\Repositories\IssueRepository;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Repositories\IssueRepository */
class IssueRepositoryTest extends TestCase {
    use RefreshDatabase;

    /** @testdox Given a parking lot in the db when createForLot is called an issue is added to the lot */
    public function testAddForLot() {
        /** @var ParkingLot $lot */
        $lot = factory(ParkingLot::class)->create();

        $repository = new IssueRepository;
        $issue = $repository->addForLot($lot, Issue::TYPE_FULL);

        $lot->refresh();

        $this->assertInstanceOf(Issue::class, $issue);
        $this->assertCount(1, $lot->issues()->get());
    }

    /** @testdox Given an entry in the db when createForEntry is called an issue is added to the entry */
    public function testAddForEntry() {
        factory(ParkingLot::class)->create();
        /** @var Entry $lot */
        $entry = factory(Entry::class)->create();

        $repository = new IssueRepository;
        $issue = $repository->addForEntry($entry, Issue::TYPE_FULL);

        $entry->refresh();

        $this->assertInstanceOf(Issue::class, $issue);
        $this->assertCount(1, $entry->issues()->get());
    }

    /** @testdox Given a non solved issue when markSolvedById is called then the issue is solved */
    public function testMakeAsSolved() {
        $lot = factory(ParkingLot::class)->create();
        $repository = new IssueRepository;

        $issue = $repository->addForLot($lot, Issue::TYPE_FULL);

        $solvedIssue = $repository->markSolvedByid($issue->id);

        $this->assertTrue($solvedIssue->solved);
    }
}