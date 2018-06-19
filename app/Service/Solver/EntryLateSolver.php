<?php
declare(strict_types=1);

namespace Parking\Service\Solver;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\Issue;
use Parking\Repositories\EntryRepository;

class EntryLateSolver extends Solver {

    /** @var EntryRepository */
    private $entryRepository;

    public function __construct(
        $nextSolver,
        EntryRepository $entryRepository
    ) {
        parent::__construct($nextSolver);
        $this->entryRepository = $entryRepository;
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function solve(Issue $issue) {
        $entry = $issue->entry()->first();

        if(!$entry instanceof Entry) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Something bad happened.');
        }

        $this->entryRepository->updateFieldsForEntry($entry, [
            'exited_at' => Carbon::now()
        ], true);
    }

    public function getHandledType(): string {
        return Issue::TYPE_LATE;
    }
}