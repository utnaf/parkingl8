<?php
declare(strict_types=1);

namespace Parking\Service\Validators;

use Illuminate\Support\MessageBag;

trait ValidationTrait {

    /** @var MessageBag|null */
    private $validationMessageBag;

    protected function areDataValid(array $data, array $rules): bool {
        $validator = validator($data, $rules);

        if($validator->fails()) {
            $this->validationMessageBag = $validator->getMessageBag();
            return false;
        }

        return true;
    }

    protected function getValidationMessageBag() {
        return $this->validationMessageBag;
    }

}