<?php

namespace Parking\Service\Solver;

use Parking\Issue;

abstract class Solver {
    /** @var null|Solver */
    private $nextSolver;

    public function __construct($nextSolver) {
        $this->nextSolver = $nextSolver;
    }

    public function resolve(Issue $issue) {
        if($issue->type !== $this->getHandledType()) {
            if($this->nextSolver instanceof self) {
                $this->nextSolver->resolve($issue);
            }
        }
        else {
            $this->solve($issue);
        }
    }

    abstract protected function solve(Issue $issue);

    abstract protected function getHandledType(): string ;
}