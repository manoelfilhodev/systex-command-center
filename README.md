# Systex Command Center

Painel executivo principal da **SYSTEX Sistemas Inteligentes**, projetado para centralizar a gestão comercial, financeira, operacional e estratégica da empresa.

O objetivo do projeto é consolidar em uma única plataforma o controle de:

- Clientes
- Leads
- CRM comercial
- Propostas
- Contratos
- Financeiro
- MRR (Monthly Recurring Revenue)
- Projetos
- Implantação
- Suporte
- Indicadores executivos

---

# Filosofia do Projeto

Este sistema nasce seguindo o padrão oficial do **Systex AI Framework**, onde toda decisão deve respeitar:

1. Estratégia
2. Estrutura
3. Segurança
4. Escalabilidade
5. Execução

Nunca o contrário.

A base do projeto não aceita improviso estrutural.

---

# Agentes Oficiais

## 👑 ATLAS

Orquestração geral, prioridade, governança e roadmap.

## 🧠 ATHENA

Regras de negócio e aderência operacional.

## 🏗️ PROMETEU

Arquitetura Laravel, escalabilidade e estrutura enterprise.

## 🗄️ GAIA

Banco de dados, modelagem e relacionamentos.

## ⚙️ VULCAN

Estrutura base, setup e fundação técnica.

## 🔴 ARES

Backend, controllers, services e APIs.

## 🎨 APOLLO

Frontend, UX, layout premium e padrão visual Systex.

## 📱 HERMES

Visão futura do app Flutter e integração mobile.

## 🧪 ORION

Qualidade, testes e mitigação de risco.

## 🛡️ HADES

Segurança, autenticação e permissões.

## 💰 CRONOS

Financeiro, contratos, indicadores e MRR.

## 📈 MERCURIUS

CRM, vendas, propostas e pipeline comercial.

## 🎯 AURORA

Branding, posicionamento e percepção de valor.

## ⚖️ THEMIS

Jurídico, contratos, SLA e proteção operacional.

## 🛰️ TITAN

Operações, implantação, entrega, suporte e Customer Success.

---

# Stack Oficial

- Laravel 13
- PHP 8+
- MySQL
- Blade
- Tailwind CSS
- Vite
- Ubuntu / WSL
- VS Code
- futura API para Flutter

Tema visual:

- Preto
- Cinza
- Vermelho apenas em detalhes

Visual premium SaaS / enterprise / clean / tecnológico.

---

# Estado Atual da Base

A base atual já contempla:

- Login, logout, proteção de rotas e perfis de acesso.
- Gestão administrativa de usuários e papéis de acesso.
- Dashboard executivo com indicadores comerciais, financeiros, operacionais e alertas críticos.
- Leads, CRM, propostas, contratos, clientes, financeiro, MRR, projetos, implantações e suporte.
- Auditoria de eventos críticos para contratos, propostas, financeiro, implantação e suporte.
- Diretoria com leitura executiva ampla e módulos operacionais protegidos por perfil.
- Testes automatizados para fluxo principal, banco, permissões, auditoria e prontidão operacional.

Documentação complementar:

- `standards/` concentra os padrões por agente e disciplina.
- `docs/production-checklist.md` concentra o checklist de homologação, produção e go-live.
- `docs/release-readiness.md` concentra o aceite final de entrega e homologação interna.

---

# Estrutura Inicial

## Banco principal

```txt
systex_command_center
```

## Tabelas iniciais

```txt
_tb_clientes
_tb_leads
_tb_propostas
_tb_proposta_itens
_tb_servicos
_tb_contratos
_tb_financeiro
_tb_mrr_historico
_tb_projetos
_tb_implantacoes
_tb_implantacao_etapas
```

---

# Fluxo Comercial Principal

```txt
Lead
→ Oportunidade
→ Diagnóstico
→ Proposta
→ Negociação
→ Fechamento
→ Contrato
→ Implantação
→ Cliente Ativo
→ Receita Recorrente
→ Expansão
```

---

# Serviços Core da SYSTEX

## Produtos

- WMS
- ERP
- CRM

## Serviços

- Desenvolvimento sob demanda
- Implantação
- Consultoria
- Suporte
- Integrações
- Customizações

## Receita Recorrente

- Mensalidade
- SLA
- Suporte mensal
- Sustentação
- Hospedagem
- Evolução contínua

---

# Setup Local

## Instalação

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

## Banco

Configurar `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=systex_command_center
DB_USERNAME=systex_user
DB_PASSWORD=******
```

## Migrations

```bash
php artisan migrate
```

## Dados de Demonstração

```bash
php artisan db:seed
```

A seed principal cria serviços core e uma massa demo executiva com lead, cliente, proposta, contrato, MRR, financeiro, projeto, implantação, suporte e auditoria.

## Frontend

```bash
npm run dev
```

## Backend

```bash
php artisan serve
```

## Validação Técnica

```bash
php artisan test
vendor/bin/pint --test
npm run build
DB_CONNECTION=sqlite DB_DATABASE=:memory: php artisan migrate:fresh --force
```

## Checklist de Produção

Antes de homologação ou go-live, seguir o arquivo:

```txt
docs/production-checklist.md
```

## Aceite de Entrega

Para validar o pacote final do MVP interno:

```txt
docs/release-readiness.md
```

---

# Roadmap

## Fase 1 — Fundação

- Ambiente Laravel
- Banco MySQL
- Dashboard inicial
- Layout base Systex
- Estrutura de migrations

## Fase 2 — Comercial

- Leads
- CRM
- Clientes
- Propostas
- Contratos

## Fase 3 — Financeiro

- Receitas
- Despesas
- MRR
- Indicadores
- Dashboard executivo

## Fase 4 — Operação

- Projetos
- Implantação
- Suporte
- SLA
- Go Live

## Fase 5 — Expansão

- API Flutter
- Portal do Cliente
- Automações
- IA operacional

---

# Governança

Nenhuma implementação deve avançar sem:

- validação arquitetural
- controle de risco
- padrão de nomenclatura
- rastreabilidade
- possibilidade real de escala
- evidência mínima de QA
- validação de segurança para fluxos sensíveis
- handoff operacional quando houver impacto em cliente

O projeto deve nascer pronto para crescer.

## Checkpoints por Agente

```txt
ATLAS      → plano, prioridade, riscos e próximo checkpoint
ATHENA     → regras de negócio e critérios de aceite
PROMETEU   → arquitetura, limites e decisões técnicas
GAIA       → modelo de dados, integridade, índices e rollback
VULCAN     → estrutura, padrões, setup e qualidade estática
ARES       → controllers, serviços, APIs e validações backend
APOLLO     → experiência web, estados de tela e responsividade
HERMES     → contratos futuros para Flutter e integração mobile
ORION      → testes, evidências e recomendação Go/No-Go
HADES      → autenticação, autorização e superfície de ataque
CRONOS     → MRR, receita, margem e indicadores financeiros
MERCURIUS  → funil, CRM, qualificação e conversão comercial
AURORA     → marca, narrativa, percepção e comunicação
THEMIS     → contratos, SLA, LGPD e responsabilidades
TITAN      → implantação, runbook, suporte e pós-go-live
```

---

# SYSTEX

**SYSTEX Sistemas Inteligentes**

Tecnologia aplicada à operação real.

Soluções robustas para logística, gestão, automação e performance empresarial.
