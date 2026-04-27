<?php

namespace App\Http\Controllers;

use App\Services\CommercialPipelineService;

class CrmController extends Controller
{
    public function __construct(
        private readonly CommercialPipelineService $pipelineService
    ) {}

    public function index()
    {
        return view('crm.index', [
            'summary' => $this->pipelineService->summary(),
            'stages' => $this->pipelineService->pipeline(),
            'taskAlerts' => $this->pipelineService->taskAlerts(),
        ]);
    }
}
