# Standards

Regras tecnicas obrigatorias do Systex AI Framework para este projeto.

## Ordem de Validacao

1. Estrategia e regra de negocio validadas por ATLAS e ATHENA.
2. Arquitetura e dados validados por PROMETEU e GAIA.
3. Estrutura, backend e frontend validados por VULCAN, ARES e APOLLO.
4. Segurança e qualidade validadas por HADES e ORION.
5. Financeiro, comercial, juridico e operacao validados por CRONOS, MERCURIUS, THEMIS e TITAN quando aplicavel.

## Minimos Obrigatorios

- Toda rota exposta deve apontar para metodo existente.
- Toda tela com acao deve ter rota, controller e feedback de erro/sucesso.
- Toda migration deve ter rollback coerente com o nome real da tabela.
- Status e enums devem ser consistentes entre migration, controller, service e view.
- Toda mudanca em fluxo critico deve ter teste ou justificativa registrada.
- Toda decisao estrutural deve gerar handoff ou ADR resumido.

## Go/No-Go

Uma entrega nao deve avancar quando houver:

- teste automatizado falhando;
- migration que nao executa no ambiente de validacao;
- rota quebravel em navegacao comum;
- ausencia de autenticacao/autorizacao em fluxo sensivel;
- ausencia de rollback ou plano operacional para mudanca de dados.
