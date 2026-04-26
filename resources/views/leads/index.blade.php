@extends('layouts.systex')

@section('content')
    <x-topbar
        title="Leads"
        subtitle="Gestão inicial do funil comercial da SYSTEX"
    />

    <div style="margin-bottom: 24px;">
        <a href="{{ route('leads.create') }}"
           style="background:#ff2a2a; color:white; padding:12px 18px; border-radius:12px; text-decoration:none; font-weight:600;">
            Novo Lead
        </a>
    </div>

    <div class="card">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="color:#a1a1aa; text-align:left; font-size:13px;">
                    <th style="padding:12px;">Nome</th>
                    <th style="padding:12px;">Empresa</th>
                    <th style="padding:12px;">Telefone</th>
                    <th style="padding:12px;">Status</th>
                    <th style="padding:12px;">Valor Estimado</th>
                    <th style="padding:12px;">Próximo Contato</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leads as $lead)
                    <tr style="border-top:1px solid rgba(255,255,255,0.06);">
                        <td style="padding:14px;">{{ $lead->nome }}</td>
                        <td style="padding:14px;">{{ $lead->empresa ?? '-' }}</td>
                        <td style="padding:14px;">{{ $lead->telefone ?? '-' }}</td>
                        <td style="padding:14px;">{{ str_replace('_', ' ', ucfirst($lead->status)) }}</td>
                        <td style="padding:14px;">R$ {{ number_format($lead->valor_estimado, 2, ',', '.') }}</td>
                        <td style="padding:14px;">
                            {{ $lead->proximo_contato ? $lead->proximo_contato->format('d/m/Y') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:24px; color:#a1a1aa;">
                            Nenhum lead cadastrado ainda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:20px;">
            {{ $leads->links() }}
        </div>
    </div>
@endsection
