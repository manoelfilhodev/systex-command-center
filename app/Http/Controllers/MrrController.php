<?php

namespace App\Http\Controllers;

use App\Services\MrrService;

class MrrController extends Controller
{
    public function __construct(
        private readonly MrrService $mrrService
    ) {}

    public function index()
    {
        $summary = $this->mrrService->currentSummary();
        $registros = $this->mrrService->paginatedHistory();

        return view('mrr.index', [
            ...$summary,
            'registros' => $registros,
        ]);
    }
}
