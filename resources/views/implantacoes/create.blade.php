@extends('layouts.systex')

@section('content')
    <div class="topbar">
        <div>
            <div class="topbar-kicker">TITAN</div>
            <h1>Nova Implantação</h1>
            <p>Criação da implantação principal vinculada ao contrato, com governança operacional e acompanhamento
                executivo.</p>
        </div>

        <div class="topbar-actions">
            <span class="system-pill">
                <span class="status-dot"></span>
                Nova Operação
            </span>
        </div>
    </div>

    <div class="page-panel">

        <form action="{{ route('implantacoes.store') }}" method="POST">
            @csrf

            <div class="grid">

                <div class="card">
                    <div class="card-title">Contrato *</div>

                    <select name="contrato_id" class="form-control" required>
                        <option value="">Selecione</option>

                        @foreach ($contratos as $contrato)
                            <option value="{{ $contrato->id }}">
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
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em andamento</option>
                        <option value="homologacao">Homologação</option>
                        <option value="go_live">Go Live</option>
                        <option value="concluida">Concluída</option>
                        <option value="pausada">Pausada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="card">
                    <div class="card-title">Data de Início</div>

                    <input type="date" name="data_inicio" class="form-control">
                </div>

                <div class="card">
                    <div class="card-title">Data Go Live</div>

                    <input type="date" name="data_go_live" class="form-control">
                </div>

                <div class="card">
                    <div class="card-title">Responsável</div>

                    <input type="text" name="responsavel" class="form-control"
                        placeholder="Responsável pela implantação">
                </div>

            </div>

            <div style="height: 20px;"></div>

            <div class="card">
                <div class="card-title">Observações</div>

                <textarea name="observacoes" rows="6" class="form-control"
                    placeholder="Observações iniciais, escopo, riscos, próximos passos..."></textarea>
            </div>

            <div style="height: 20px;"></div>

            <div class="form-actions">
                <a href="{{ route('implantacoes.index') }}" class="btn-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn-primary">
                    Salvar Implantação
                </button>
            </div>

        </form>

    </div>
@endsection
