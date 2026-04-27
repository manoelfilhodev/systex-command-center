@extends('layouts.systex')

@section('content')

    <div class="topbar">
        <div>
            <div class="topbar-kicker">MERCURIUS + CRONOS</div>
            <h1>Nova Proposta</h1>
            <p>Construção comercial da proposta com implantação, recorrência e composição de serviços.</p>
        </div>

        <div class="topbar-actions">
            <a href="{{ route('propostas.index') }}" class="btn-secondary">Voltar</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            <strong>Existem erros no formulário:</strong>
            <ul style="margin-top:10px; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('propostas.store') }}">
        @csrf

        <div class="page-panel">
            <div class="form-grid">

                <div class="form-group">
                    <label>Lead Vinculado</label>
                    <select name="lead_id">
                        <option value="">Selecione</option>

                        @foreach ($leads as $lead)
                            <option value="{{ $lead->id }}"
                                {{ (string) $leadSelecionado === (string) $lead->id ? 'selected' : '' }}>
                                {{ $lead->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Status Comercial *</label>
                    <select name="status" required>
                        <option value="rascunho">Rascunho</option>
                        <option value="enviada">Enviada</option>
                        <option value="negociacao">Negociação</option>
                        <option value="aprovada">Aprovada</option>
                        <option value="recusada">Recusada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Título da Proposta *</label>
                    <input type="text" name="titulo" required
                        placeholder="Ex.: Implantação WMS + Aplicativo Operacional">
                </div>

                <div class="form-group">
                    <label>Valor de Implantação</label>
                    <input type="number" step="0.01" name="valor_implantacao" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label>Valor Recorrente (Mensal)</label>
                    <input type="number" step="0.01" name="valor_recorrente" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label>Data de Envio</label>
                    <input type="date" name="data_envio">
                </div>

                <div class="form-group">
                    <label>Data de Validade</label>
                    <input type="date" name="data_validade">
                </div>

                <div class="form-group full">
                    <label>Escopo</label>
                    <textarea name="escopo" rows="4" placeholder="Descreva o escopo principal da proposta..."></textarea>
                </div>

                <div class="form-group full">
                    <label>Observações</label>
                    <textarea name="observacoes" rows="4" placeholder="Observações comerciais, negociação, condições especiais..."></textarea>
                </div>

            </div>
        </div>

        <div style="height: 20px;"></div>

        <div class="page-panel">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <div>
                    <h3 style="font-size:20px; font-weight:700;">Itens da Proposta</h3>
                    <p style="color:#a1a1aa; margin-top:6px;">Serviços e composições comerciais da proposta</p>
                </div>

                <button type="button" class="btn-secondary" id="add-item">
                    + Adicionar Item
                </button>
            </div>

            <div id="itens-wrapper">
                <div class="item-card"
                    style="margin-bottom:20px; padding:20px; border:1px solid rgba(255,255,255,0.06); border-radius:16px;">
                    <div class="form-grid">

                        <div class="form-group">
                            <label>Descrição do Item</label>
                            <input type="text" name="itens[0][descricao]" placeholder="Ex.: Implantação inicial">
                        </div>

                        <div class="form-group">
                            <label>Tipo</label>
                            <select name="itens[0][tipo]">
                                <option value="implantacao">Implantação</option>
                                <option value="mensalidade">Mensalidade</option>
                                <option value="customizacao">Customização</option>
                                <option value="suporte">Suporte</option>
                                <option value="integracao">Integração</option>
                                <option value="consultoria">Consultoria</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantidade</label>
                            <input type="number" name="itens[0][quantidade]" value="1">
                        </div>

                        <div class="form-group">
                            <label>Valor Unitário</label>
                            <input type="number" step="0.01" name="itens[0][valor_unitario]" placeholder="0.00">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div style="height: 20px;"></div>

        <div class="page-panel">
            <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap:20px;">
                <div class="card">
                    <div class="card-title">Implantação</div>
                    <div class="card-value" id="summary-implantacao">R$ 0,00</div>
                </div>

                <div class="card">
                    <div class="card-title">Recorrente Mensal</div>
                    <div class="card-value" id="summary-recorrente">R$ 0,00</div>
                </div>

                <div class="card">
                    <div class="card-title">Total da Proposta</div>
                    <div class="card-value" id="summary-total">R$ 0,00</div>
                </div>
            </div>
        </div>

        <div style="height: 20px;"></div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Salvar Proposta</button>
            <a href="{{ route('propostas.index') }}" class="btn-secondary">Cancelar</a>
        </div>

    </form>

    <script>
        let itemIndex = 1;

        const money = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });

        function calculateTotals() {
            const implantacaoBase = parseFloat(document.querySelector('[name="valor_implantacao"]').value || 0);
            const recorrenteBase = parseFloat(document.querySelector('[name="valor_recorrente"]').value || 0);

            let itensTotal = 0;

            document.querySelectorAll('.item-card').forEach((card) => {
                const qtd = parseFloat(card.querySelector('[name*="[quantidade]"]')?.value || 0);
                const valor = parseFloat(card.querySelector('[name*="[valor_unitario]"]')?.value || 0);

                itensTotal += qtd * valor;
            });

            const total = implantacaoBase + recorrenteBase + itensTotal;

            document.getElementById('summary-implantacao').innerText = money.format(implantacaoBase);
            document.getElementById('summary-recorrente').innerText = money.format(recorrenteBase);
            document.getElementById('summary-total').innerText = money.format(total);
        }

        document.getElementById('add-item').addEventListener('click', function() {
            const wrapper = document.getElementById('itens-wrapper');

            const item = document.createElement('div');
            item.className = 'item-card';
            item.style =
                'margin-bottom:20px; padding:20px; border:1px solid rgba(255,255,255,0.06); border-radius:16px;';

            item.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <strong>Item adicional</strong>
                <button type="button" class="btn-danger remove-item">Remover</button>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Descrição do Item</label>
                    <input type="text" name="itens[${itemIndex}][descricao]" placeholder="Ex.: Integração SAP">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <select name="itens[${itemIndex}][tipo]">
                        <option value="implantacao">Implantação</option>
                        <option value="mensalidade">Mensalidade</option>
                        <option value="customizacao">Customização</option>
                        <option value="suporte">Suporte</option>
                        <option value="integracao">Integração</option>
                        <option value="consultoria">Consultoria</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Quantidade</label>
                    <input type="number" name="itens[${itemIndex}][quantidade]" value="1">
                </div>

                <div class="form-group">
                    <label>Valor Unitário</label>
                    <input type="number" step="0.01" name="itens[${itemIndex}][valor_unitario]" placeholder="0.00">
                </div>
            </div>
        `;

            wrapper.appendChild(item);
            itemIndex++;
            calculateTotals();
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-item')) {
                event.target.closest('.item-card').remove();
                calculateTotals();
            }
        });

        document.addEventListener('input', function(event) {
            if (
                event.target.name === 'valor_implantacao' ||
                event.target.name === 'valor_recorrente' ||
                event.target.name?.includes('[quantidade]') ||
                event.target.name?.includes('[valor_unitario]')
            ) {
                calculateTotals();
            }
        });

        calculateTotals();
    </script>

@endsection
