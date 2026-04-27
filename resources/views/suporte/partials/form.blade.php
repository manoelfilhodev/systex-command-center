<div class="page-panel">
    <div class="form-grid">
        <div class="form-group full">
            <label>Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo', $chamado->titulo ?? '') }}" required>
        </div>

        <div class="form-group">
            <label>Cliente</label>
            <select name="cliente_id">
                <option value="">Herdar do contrato ou sem cliente</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" @selected(old('cliente_id', $chamado->cliente_id ?? null) == $cliente->id)>
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
                    <option value="{{ $contrato->id }}" @selected(old('contrato_id', $chamado->contrato_id ?? null) == $contrato->id)>
                        {{ $contrato->numero }} - {{ $contrato->cliente->nome_fantasia ?? $contrato->cliente->razao_social ?? 'Cliente' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Categoria *</label>
            <select name="categoria" required>
                @foreach(['incidente', 'duvida', 'melhoria', 'integracao', 'infraestrutura', 'outros'] as $categoria)
                    <option value="{{ $categoria }}" @selected(old('categoria', $chamado->categoria ?? 'incidente') === $categoria)>
                        {{ ucfirst($categoria) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Prioridade *</label>
            <select name="prioridade" required>
                @foreach(['baixa', 'media', 'alta', 'critica'] as $prioridade)
                    <option value="{{ $prioridade }}" @selected(old('prioridade', $chamado->prioridade ?? 'media') === $prioridade)>
                        {{ ucfirst($prioridade) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status" required>
                @foreach(['aberto', 'em_atendimento', 'aguardando_cliente', 'resolvido', 'cancelado'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $chamado->status ?? 'aberto') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Canal *</label>
            <select name="canal" required>
                @foreach(['email', 'whatsapp', 'telefone', 'portal', 'interno'] as $canal)
                    <option value="{{ $canal }}" @selected(old('canal', $chamado->canal ?? 'interno') === $canal)>
                        {{ ucfirst($canal) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Aberto em *</label>
            <input type="datetime-local" name="aberto_em" value="{{ old('aberto_em', isset($chamado?->aberto_em) ? $chamado->aberto_em->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="form-group">
            <label>Prazo SLA</label>
            <input type="datetime-local" name="prazo_sla" value="{{ old('prazo_sla', isset($chamado?->prazo_sla) ? $chamado->prazo_sla->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="form-group">
            <label>Resolvido em</label>
            <input type="datetime-local" name="resolvido_em" value="{{ old('resolvido_em', isset($chamado?->resolvido_em) ? $chamado->resolvido_em->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="form-group">
            <label>Responsável</label>
            <input type="text" name="responsavel" value="{{ old('responsavel', $chamado->responsavel ?? '') }}">
        </div>

        <div class="form-group full">
            <label>Descrição</label>
            <textarea name="descricao" rows="5">{{ old('descricao', $chamado->descricao ?? '') }}</textarea>
        </div>

        <div class="form-group full">
            <label>Resolução</label>
            <textarea name="resolucao" rows="5">{{ old('resolucao', $chamado->resolucao ?? '') }}</textarea>
        </div>
    </div>
</div>
