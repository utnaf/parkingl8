<?php
declare(strict_types=1);

namespace Parking\Service\Solver;

use Parking\Repositories\IssueRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SolverService {

    /** @var Solver */
    private $firstSolved;

    /** @var IssueRepository */
    private $issueRepository;

    public function __construct(
        Solver $firstSolved,
        IssueRepository $issueRepository
    ) {
        $this->firstSolved = $firstSolved;
        $this->issueRepository = $issueRepository;
    }

    /** @throws NotFoundHttpException */
    public function solveById(int $id) {
        $issue = $this->issueRepository->getById($id);
        $this->firstSolved->resolve($issue);
        return $this->issueRepository->markIssueAsSolved($issue);
    }

}