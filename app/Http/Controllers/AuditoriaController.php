<?php

namespace App\Http\Controllers;

use App\Services\AuditoriaService;

class AuditoriaController extends Controller
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        return view('auditoria.index', [
            'summary' => $this->auditoriaService->summary(),
            'eventos' => $this->auditoriaService->recentes(),
        ]);
    }
}
