<?php
declare(strict_types=1);

namespace Parking\Service\Solver;

use Illuminate\Http\Response;
use Parking\Entry;
use Parking\Issue;
use Parking\Repositories\EntryRepository;
use Parking\Service\PriceCalculatorService;

class NotPayedSolver extends Solver {

    /** @var EntryRepository */
    private $entryRepository;

    /** @var PriceCalculatorService */
    private $priceCalculatorService;

    public function __construct(
        $nextSolver,
        EntryRepository $entryRepository,
        PriceCalculatorService $priceCalculatorService
    ) {
        parent::__construct($nextSolver);
        $this->entryRepository = $entryRepository;
        $this->priceCalculatorService = $priceCalculatorService;
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
            'price' => $this->priceCalculatorService->calculateForEntry($entry)
        ], true);
    }

    public function getHandledType(): string {
        return Issue::TYPE_NOT_PAYED;
    }
}