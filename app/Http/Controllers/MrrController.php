<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\MrrHistorico;
use Illuminate\Http\Request;

class MrrController extends Controller
{
    public function index()
    {
        $anoAtual = now()->year;
        $mesAtual = now()->month;

        $mrrAtual = MrrHistorico::where('ano', $anoAtual)
            ->where('mes', $mesAtual)
            ->where('status', 'confirmado')
            ->sum('valor_mrr');

        $mrrPrevisto = MrrHistorico::where('ano', $anoAtual)
            ->where('mes', $mesAtual)
            ->whereIn('status', ['previsto', 'confirmado'])
            ->sum('valor_mrr');

        $contratosAtivos = Contrato::where('status', 'ativo')->count();

        $receitaMensalContratada = Contrato::where('status', 'ativo')->sum('valor_mensal');

        $registros = MrrHistorico::with(['cliente', 'contrato'])
            ->latest()
            ->paginate(10);

        $contratos = Contrato::where('status', 'ativo')
            ->where('valor_mensal', '>', 0)
            ->get();

        foreach ($contratos as $contrato) {
            MrrHistorico::updateOrCreate(
                [
                    'cliente_id' => $contrato->cliente_id,
                    'contrato_id' => $contrato->id,
                    'ano' => now()->year,
                    'mes' => now()->month,
                ],
                [
                    'valor_mrr' => $contrato->valor_mensal,
                    'status' => 'confirmado',
                ]
            );
        }

        return view('mrr.index', compact(
            'mrrAtual',
            'mrrPrevisto',
            'contratosAtivos',
            'receitaMensalContratada',
            'registros',
            'anoAtual',
            'mesAtual'
        ));
    }
}
