# Backend Standard

## Responsaveis

- ARES: controllers, services, validacoes e APIs.
- ATHENA: regras de negocio.
- HADES: controles de acesso quando houver fluxo sensivel.

## Regras

- Requests devem validar todos os campos gravados.
- Fluxos transacionais com multiplas escritas devem usar transaction.
- Acoes destrutivas devem usar metodo HTTP apropriado e CSRF nas views.
- Respostas web devem redirecionar com mensagem de sucesso ou devolver erros de validacao.
- Controllers nao devem expor endpoints sem view, metodo ou criterio de aceite.

## Criterio de Pronto

- Teste funcional cobre caminho feliz principal.
- Validacao impede status, tipo e relacionamentos invalidos.
- Fluxos financeiros ou contratuais geram rastreabilidade minima.
