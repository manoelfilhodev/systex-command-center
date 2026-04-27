# Database Standard

## Responsaveis

- GAIA: modelagem, integridade e rollback.
- CRONOS: indicadores financeiros e MRR.
- THEMIS: retencao e dados contratuais sensiveis.

## Regras

- Tabelas de dominio seguem o prefixo `_tb_`.
- Toda migration deve ter `down()` coerente com os nomes reais.
- Enums devem estar alinhados com validacoes, services e views.
- Relacionamentos obrigatorios devem usar foreign key.
- Indicadores financeiros devem preservar origem do dado.

## Criterio de Pronto

- `php artisan migrate:fresh --force` passa no ambiente validado.
- Rollback nao aponta para tabela inexistente.
- Campos de valor monetario usam decimal com duas casas.
