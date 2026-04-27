<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\ImplantacaoController;
use App\Http\Controllers\ImplantacaoEtapaController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadInteracaoController;
use App\Http\Controllers\LeadTarefaController;
use App\Http\Controllers\MrrController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\PropostaController;
use App\Http\Controllers\SuporteChamadoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:diretoria,comercial,financeiro,operacao')
        ->name('dashboard.index');

    Route::get('/auditoria', [AuditoriaController::class, 'index'])
        ->middleware('role:diretoria')
        ->name('auditoria.index');

    Route::resource('usuarios', UserController::class)
        ->except(['show'])
        ->middleware('role:admin')
        ->parameters(['usuarios' => 'usuario']);

    Route::resource('leads', LeadController::class)
        ->except(['index', 'show'])
        ->middleware('role:comercial');
    Route::resource('leads', LeadController::class)
        ->only(['index', 'show'])
        ->middleware('role:comercial,diretoria');
    Route::patch('/leads/{lead}/stage', [LeadController::class, 'advanceStage'])
        ->middleware('role:comercial')
        ->name('leads.stage.update');
    Route::post('/leads/{lead}/interacoes', [LeadInteracaoController::class, 'store'])
        ->middleware('role:comercial')
        ->name('leads.interacoes.store');
    Route::delete('/leads/interacoes/{interacao}', [LeadInteracaoController::class, 'destroy'])
        ->middleware('role:comercial')
        ->name('leads.interacoes.destroy');
    Route::post('/leads/{lead}/tarefas', [LeadTarefaController::class, 'store'])
        ->middleware('role:comercial')
        ->name('leads.tarefas.store');
    Route::patch('/leads/tarefas/{tarefa}/complete', [LeadTarefaController::class, 'complete'])
        ->middleware('role:comercial')
        ->name('leads.tarefas.complete');
    Route::delete('/leads/tarefas/{tarefa}', [LeadTarefaController::class, 'destroy'])
        ->middleware('role:comercial')
        ->name('leads.tarefas.destroy');

    Route::get('/crm', [CrmController::class, 'index'])
        ->middleware('role:comercial,diretoria')
        ->name('crm.index');

    Route::resource('propostas', PropostaController::class)
        ->except(['index', 'show'])
        ->middleware('role:comercial');
    Route::resource('propostas', PropostaController::class)
        ->only(['index', 'show'])
        ->middleware('role:comercial,diretoria');
    Route::patch('/propostas/{proposta}/approve', [PropostaController::class, 'approve'])
        ->middleware('role:comercial')
        ->name('propostas.approve');
    Route::resource('contratos', ContratoController::class)
        ->except(['index', 'show'])
        ->middleware('role:financeiro');
    Route::resource('contratos', ContratoController::class)
        ->only(['index', 'show'])
        ->middleware('role:financeiro,diretoria');
    Route::resource('clientes', ClienteController::class)
        ->except(['index', 'show'])
        ->middleware('role:comercial,financeiro,operacao');
    Route::resource('clientes', ClienteController::class)
        ->only(['index', 'show'])
        ->middleware('role:comercial,financeiro,operacao,diretoria');
    Route::get('/mrr', [MrrController::class, 'index'])
        ->middleware('role:financeiro,diretoria')
        ->name('mrr.index');
    Route::resource('financeiro', FinanceiroController::class)
        ->except(['index', 'show'])
        ->middleware('role:financeiro');
    Route::resource('financeiro', FinanceiroController::class)
        ->only(['index', 'show'])
        ->middleware('role:financeiro,diretoria');
    Route::resource('projetos', ProjetoController::class)
        ->except(['index', 'show'])
        ->middleware('role:operacao');
    Route::resource('projetos', ProjetoController::class)
        ->only(['index', 'show'])
        ->middleware('role:operacao,diretoria');
    Route::resource('implantacoes', ImplantacaoController::class)
        ->except(['index', 'show'])
        ->middleware('role:operacao')
        ->parameters(['implantacoes' => 'implantacao']);
    Route::resource('implantacoes', ImplantacaoController::class)
        ->only(['index', 'show'])
        ->middleware('role:operacao,diretoria')
        ->parameters(['implantacoes' => 'implantacao']);
    Route::resource('suporte', SuporteChamadoController::class)
        ->except(['index', 'show'])
        ->middleware('role:operacao')
        ->parameters(['suporte' => 'suporte']);
    Route::resource('suporte', SuporteChamadoController::class)
        ->only(['index', 'show'])
        ->middleware('role:operacao,diretoria')
        ->parameters(['suporte' => 'suporte']);
    Route::post('/implantacoes/{implantacao}/etapas', [ImplantacaoEtapaController::class, 'store'])
        ->middleware('role:operacao')
        ->name('implantacoes.etapas.store');

    Route::put('/implantacoes/etapas/{etapa}', [ImplantacaoEtapaController::class, 'update'])
        ->middleware('role:operacao')
        ->name('implantacoes.etapas.update');

    Route::delete('/implantacoes/etapas/{etapa}', [ImplantacaoEtapaController::class, 'destroy'])
        ->middleware('role:operacao')
        ->name('implantacoes.etapas.destroy');
});
