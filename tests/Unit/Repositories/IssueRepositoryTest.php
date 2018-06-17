<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;
use Parking\Repositories\IssueRepository;
use Parking\User;
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

    /** @testdox Given 3 elements in the db when I call getAll then it gives me all the 3 issues */
    public function testGetAll() {
        factory(ParkingLot::class)->create();
        factory(Issue::class, 3)->create();

        $repository = new IssueRepository;

        $issues = $repository->getAll();
        $this->assertCount(3, $issues);
    }

    /** @testdox Given 5 elements in the db when I count them then it gives me only the open issues */
    public function testCount() {
        factory(ParkingLot::class)->create();
        factory(Issue::class, 3)->create();
        factory(Issue::class, 3)->create([
            'solved' => 1
        ]);

        $repository = new IssueRepository;

        $issuesCount = $repository->openIssueCount();
        $this->assertEquals(3, $issuesCount);
    }

    /** @testdox Given an issue when update is called it sets the user and the solved state to 1 */
    public function testUpdate() {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        factory(ParkingLot::class)->create();
        $issue = factory(Issue::class)->create();
        $issue->refresh();

        $repository = new IssueRepository;
        $newIssue = $repository->solveIssue(1);
        $newIssue->refresh();

        $this->assertEquals(1, $newIssue->solved);
        $this->assertInstanceOf(User::class, $newIssue->completedBy()->first());
    }

    /**
     * @testdox When update is called on a non existing issue it throws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testUpdateFails() {
        factory(ParkingLot::class)->create();
        factory(Issue::class)->create();

        $repository = new IssueRepository;
        $newIssue = $repository->solveIssue(2);
    }
}