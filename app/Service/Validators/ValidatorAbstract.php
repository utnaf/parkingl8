<?php

namespace Parking\Service\Validators;

use Parking\Entry;

abstract class ValidatorAbstract implements Validator {

    /** @var Entry */
    protected $entry;

    public function forEntry(Entry $entry): Validator {
        $this->entry = $entry;

        return $this;
    }

}