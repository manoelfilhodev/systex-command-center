@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Editar Implantação</h1>
            <p>Atualização executiva da implantação, acompanhamento operacional e governança de entrega.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Operação em andamento
            </span>
        </div>
    </div>

    <div class="page-panel">

        <form action="{{ route('implantacoes.update', $implantacao) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid">

                <div class="card">
                    <div class="card-title">Contrato *</div>

                    <select name="contrato_id" class="form-control" required>
                        @foreach ($contratos as $contrato)
                            <option value="{{ $contrato->id }}" @selected($implantacao->contrato_id == $contrato->id)>
                                {{ $contrato->numero }}
                                —
                                {{ $contrato->cliente->nome_fantasia ?? ($contrato->cliente->razao_social ?? 'Cliente') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="card">
                    <div class="card-title">Status *</div>

                    <select name="status" class="form-control" required>
                        @foreach ([
            'pendente' => 'Pendente',
            'em_andamento' => 'Em andamento',
            'homologacao' => 'Homologação',
            'go_live' => 'Go Live',
            'concluida' => 'Concluída',
            'pausada' => 'Pausada',
            'cancelada' => 'Cancelada',
        ] as $key => $label)
                            <option value="{{ $key }}" @selected($implantacao->status === $key)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="card">
                    <div class="card-title">Data de Início</div>

                    <input type="date" name="data_inicio" class="form-control" value="{{ $implantacao->data_inicio }}">
                </div>

                <div class="card">
                    <div class="card-title">Data Go Live</div>

                    <input type="date" name="data_go_live" class="form-control" value="{{ $implantacao->data_go_live }}">
                </div>

                <div class="card">
                    <div class="card-title">Responsável</div>

                    <input type="text" name="responsavel" class="form-control" value="{{ $implantacao->responsavel }}"
                        placeholder="Responsável pela implantação">
                </div>

            </div>

            <div style="height: 20px;"></div>

            <div class="card">
                <div class="card-title">Observações</div>

                <textarea name="observacoes" rows="6" class="form-control"
                    placeholder="Observações operacionais, riscos, próximos passos...">{{ $implantacao->observacoes }}</textarea>
            </div>

            <div style="height: 20px;"></div>

            <div class="form-actions">
                <a href="{{ route('implantacoes.show', $implantacao) }}" class="btn-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn-primary">
                    Atualizar Implantação
                </button>
            </div>

        </form>

    </div>
@endsection
