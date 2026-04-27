# Security Standard

## Responsaveis

- HADES: autenticacao, autorizacao e superficie de ataque.
- THEMIS: privacidade, LGPD e responsabilidades.
- ORION: validacao de regressao em fluxos sensiveis.

## Regras

- Fluxos com dados comerciais, financeiros ou contratuais devem exigir autenticacao antes de producao.
- Permissoes devem ser definidas por papel antes de expor operacoes destrutivas.
- Dados sensiveis nao devem ser logados em texto aberto.
- Formularios web devem usar CSRF.
- Uploads e arquivos contratuais devem ter validacao de tipo, tamanho e armazenamento privado.

## Criterio de Pronto

- Middleware de autenticacao aplicado em rotas sensiveis antes de Go-Live.
- Politicas de acesso documentadas.
- Risco residual aprovado por ATLAS/HADES quando houver excecao.
