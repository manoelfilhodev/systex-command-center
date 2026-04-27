<div class="page-panel">
    <div class="form-grid">
        <div class="form-group full">
            <label>Nome do Projeto *</label>
            <input type="text" name="nome" value="{{ old('nome', $projeto->nome ?? '') }}" required>
        </div>

        <div class="form-group">
            <label>Cliente</label>
            <select name="cliente_id">
                <option value="">Herdar do contrato ou sem cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @selected(old('cliente_id', $projeto->cliente_id ?? null) == $cliente->id)>
                        {{ $cliente->nome_fantasia ?? $cliente->razao_social }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Contrato</label>
            <select name="contrato_id">
                <option value="">Sem contrato vinculado</option>
                @foreach($contratos as $contrato)
                    <option value="{{ $contrato->id }}" @selected(old('contrato_id', $projeto->contrato_id ?? null) == $contrato->id)>
                        {{ $contrato->numero }} - {{ $contrato->cliente->nome_fantasia ?? $contrato->cliente->razao_social ?? 'Cliente' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Tipo *</label>
            <select name="tipo" required>
                @foreach(['wms' => 'WMS', 'erp' => 'ERP', 'crm' => 'CRM', 'desenvolvimento_sob_demanda' => 'Desenvolvimento sob demanda'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('tipo', $projeto->tipo ?? 'wms') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                @foreach(['planejado', 'em_andamento', 'pausado', 'homologacao', 'concluido', 'cancelado'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $projeto->status ?? 'planejado') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Data de Início</label>
            <input type="date" name="data_inicio" value="{{ old('data_inicio', isset($projeto?->data_inicio) ? $projeto->data_inicio->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Data Prevista</label>
            <input type="date" name="data_prevista_entrega" value="{{ old('data_prevista_entrega', isset($projeto?->data_prevista_entrega) ? $projeto->data_prevista_entrega->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Data de Entrega</label>
            <input type="date" name="data_entrega" value="{{ old('data_entrega', isset($projeto?->data_entrega) ? $projeto->data_entrega->format('Y-m-d') : '') }}">
        </div>

        <div class="form-group">
            <label>Responsável</label>
            <input type="text" name="responsavel" value="{{ old('responsavel', $projeto->responsavel ?? '') }}">
        </div>

        <div class="form-group full">
            <label>Descrição</label>
            <textarea name="descricao" rows="5">{{ old('descricao', $projeto->descricao ?? '') }}</textarea>
        </div>
    </div>
</div>
