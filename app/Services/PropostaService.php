<?php

namespace App\Services;

use App\Models\Proposta;

class PropostaService
{
    public function syncLeadStatus(Proposta $proposta): void
    {
        if (! $proposta->lead) {
            return;
        }

        $leadStatus = match ($proposta->status) {
            'enviada' => 'proposta_enviada',
            'negociacao' => 'negociacao',
            'aprovada' => 'convertido',
            'recusada', 'cancelada' => 'perdido',
            default => null,
        };

        if ($leadStatus) {
            $proposta->lead->update(['status' => $leadStatus]);
        }
    }
}
