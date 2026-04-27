# Architecture Standard

## Responsaveis

- ATLAS: ordem de execucao e riscos.
- PROMETEU: limites arquiteturais e decisoes tecnicas.
- VULCAN: estrutura do repositorio.

## Regras

- Controllers devem orquestrar requests, validacao e resposta; regra reutilizavel deve ir para services.
- Modulos devem manter nomes consistentes entre rota, controller, model, migration e view.
- Toda decisao estrutural relevante deve ser registrada em handoff ou ADR resumido.
- Rotas resource so devem ser usadas quando o controller implementar o contrato completo.
- Mudancas estruturais devem preservar caminho de rollback ou registrar risco residual.

## Criterio de Pronto

- `php artisan route:list` executa sem erro.
- `vendor/bin/pint --test` passa.
- Nao ha rota exposta apontando para metodo inexistente.
