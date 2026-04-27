# Checklist de Producao Systex

Este checklist valida a base minima para colocar o Systex Command Center em homologacao ou producao com rastreabilidade, seguranca e previsibilidade operacional.

## 1. Ambiente

- Definir `APP_ENV=production`.
- Definir `APP_DEBUG=false`.
- Definir `APP_URL` com o dominio oficial HTTPS.
- Definir `APP_TIMEZONE=America/Sao_Paulo`.
- Manter `APP_LOCALE`, `APP_FALLBACK_LOCALE` e `APP_FAKER_LOCALE` como `pt_BR`.
- Gerar `APP_KEY` com `php artisan key:generate`.
- Guardar segredos fora do repositorio.

## 2. Banco de Dados

- Criar banco MySQL `systex_command_center`.
- Criar usuario dedicado com permissao apenas no banco do projeto.
- Validar backup automatico antes do primeiro uso real.
- Executar `php artisan migrate --force`.
- Conferir as tabelas principais, incluindo clientes, leads, propostas, contratos, financeiro, MRR, projetos, implantacoes, suporte e auditoria.

## 3. Build e Otimizacao

- Executar `composer install --no-dev --optimize-autoloader`.
- Executar `npm ci`.
- Executar `npm run build`.
- Executar `php artisan config:cache`.
- Executar `php artisan route:cache`.
- Executar `php artisan view:cache`.

## 4. Seguranca

- Usar HTTPS em todos os acessos.
- Definir `SESSION_SECURE_COOKIE=true` em producao.
- Definir `SESSION_ENCRYPT=true` em producao.
- Manter `SESSION_HTTP_ONLY=true`.
- Validar acesso por perfil: admin, diretoria, comercial, financeiro e operacao.
- Conferir se diretoria permanece em leitura nos modulos executivos.
- Revisar usuarios administradores antes do go-live.

## 5. Operacao

- Configurar queue worker quando houver filas ativas: `php artisan queue:work`.
- Configurar scheduler: `php artisan schedule:run` a cada minuto.
- Definir politica de logs e retencao.
- Monitorar erros, tempo de resposta e uso de disco.
- Registrar responsavel operacional pelo painel.

## 6. Qualidade

- Executar `php artisan test`.
- Executar `vendor/bin/pint --test`.
- Executar `DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --force`.
- Executar navegacao manual dos fluxos principais:
  - login e logout
  - dashboard executivo
  - lead para proposta
  - proposta aprovada para contrato
  - contrato para MRR
  - financeiro
  - projeto e implantacao
  - chamado de suporte
  - auditoria

## 7. Go/No-Go

O go-live so deve avancar quando:

- migrations executam sem erro;
- suite de testes passa;
- build frontend conclui;
- perfis de acesso foram conferidos;
- backup foi validado;
- usuario administrador oficial foi criado;
- checklist de seguranca foi aprovado.

