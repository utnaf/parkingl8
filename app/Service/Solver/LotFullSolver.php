<?php
declare(strict_types=1);

namespace Parking\Service\Solver;

use Parking\Issue;

class LotFullSolver extends Solver {

    public function solve(Issue $issue) {
        // this issue can not be solved
    }

    protected function getHandledType(): string {
        return Issue::TYPE_FULL;
    }
}