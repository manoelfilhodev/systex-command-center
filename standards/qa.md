# QA Standard

## Responsaveis

- ORION: estrategia, evidencia e Go/No-Go.
- ATLAS: bloqueios e risco residual.

## Regras

- Nenhuma entrega deve sair com teste automatizado falhando.
- Cada modulo critico deve ter ao menos um teste de caminho feliz.
- Bugs corrigidos devem ganhar teste quando forem regressivos ou de alto risco.
- Falhas conhecidas devem ser registradas com severidade e mitigacao.

## Criterio de Pronto

- `php artisan test` passa.
- `vendor/bin/pint --test` passa.
- Build frontend passa quando houver alteracao de view/asset.
