<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Parking\Repositories\IssueRepository;
use Parking\Service\Solver\SolverService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class IssuesController extends Controller {

    public function index(IssueRepository $issueRepository) {
        $issues = $issueRepository->getAll();

        return view('issues.list', [
            'issues' => $issues
        ]);
    }

    public function update(Request $request, string $id, SolverService $solverService) {
        if($request->get('action') !== 'solve') {
            return redirect()->back();
        }

        try {
            $solverService->solveById((int) $id);
        } catch (NotFoundHttpException $e) {
            abort($e->getStatusCode(), $e->getMessage());
        }

        return Redirect::route('issues')->with('success', true);
    }
}