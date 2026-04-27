# Release Readiness - Systex Command Center

## Status

Status atual: pronto para homologacao interna.

O Systex Command Center possui base funcional para operacao executiva interna, com autenticacao, perfis, modulos comerciais, financeiros, operacionais, auditoria, massa demo e validacao automatizada.

## Entregas Validadas

### Acesso e Seguranca

- Login e logout.
- Perfis: admin, diretoria, comercial, financeiro e operacao.
- Admin com gestao de usuarios.
- Diretoria com leitura executiva.
- Bloqueio de modulos por perfil.
- Protecao contra exclusao do proprio usuario.
- Protecao contra remocao do ultimo administrador.

### Comercial

- Leads.
- CRM por estagio.
- Interacoes e tarefas de lead.
- Propostas.
- Aprovacao de proposta com conversao do lead.
- Preenchimento de contrato a partir de proposta aprovada.

### Financeiro

- Contratos.
- Lancamentos financeiros.
- MRR confirmado por contrato ativo.
- Indicadores de receita, despesa, saldo, pendencias e vencimentos.

### Operacao

- Projetos.
- Implantacoes.
- Etapas de implantacao.
- Progresso, bloqueios e riscos de go-live.
- Chamados de suporte.
- SLA e chamados vencidos.

### Governanca

- Auditoria de eventos criticos.
- Checklist de producao.
- Padroes por agente em `standards/`.
- Massa demo idempotente para validacao executiva.

## Comandos Oficiais de Validacao

```bash
php artisan test
vendor/bin/pint --test
npm run build
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --seed --force
```

## Acessos de Teste

Usuarios criados anteriormente no banco local:

```txt
manoel.filho@systex.com.br
isabela.souredo@systex.com.br
senha: nvbb261214
```

Usuario demo criado pela seed:

```txt
demo.admin@systex.com.br
senha: systex-demo
```

## Criterios de Aceite para Homologacao

- Login realizado com usuario admin.
- Criacao de novo usuario por perfil.
- Navegacao do menu respeitando perfil.
- Dashboard carregando com indicadores da massa demo.
- Fluxo lead, proposta, contrato e MRR validado.
- Fluxo financeiro validado.
- Fluxo projeto, implantacao e suporte validado.
- Auditoria exibindo eventos criticos.
- Suite automatizada passando.
- Build frontend passando.
- Migration com seed passando em ambiente limpo.

## Riscos Residuais

- Producao depende de servidor, dominio HTTPS, backup real e configuracao de filas/scheduler.
- Integracao mobile/API Flutter ainda nao faz parte deste MVP.
- Portal do cliente, automacoes e IA operacional permanecem como expansao.
- Politicas formais de LGPD, SLA juridico e retencao de dados ainda precisam de validacao institucional antes de uso externo.

## Recomendacao

Go para homologacao interna.

Nao recomendar uso externo em producao antes de:

- configurar HTTPS;
- revisar administradores oficiais;
- validar backup e restore;
- definir rotina operacional;
- revisar politicas juridicas e LGPD;
- executar checklist de producao completo.

