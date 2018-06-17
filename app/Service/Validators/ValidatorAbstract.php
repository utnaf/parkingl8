<?php

namespace Parking\Service\Validators;

use Parking\Entry;
use Parking\Repositories\IssueRepository;

abstract class ValidatorAbstract implements Validator {

    /** @var Entry */
    protected $entry;

    /** @var IssueRepository*/
    protected $issueRepository;

    public function __construct(IssueRepository $issueRepository) {
        $this->issueRepository = $issueRepository;
    }

    public function forEntry(Entry $entry): Validator {
        $this->entry = $entry;

        return $this;
    }

}