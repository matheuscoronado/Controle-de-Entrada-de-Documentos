# 📂 SCED — Sistema de Controle de Entrada de Documentos

> Sistema web desenvolvido em Laravel para registrar, controlar e acompanhar a entrada e movimentação de documentos dentro de uma organização.

---

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura do Sistema](#arquitetura-do-sistema)
- [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
- [Perfis de Acesso](#perfis-de-acesso)
- [Telas do Sistema](#telas-do-sistema)
- [Geração de Relatórios](#geração-de-relatórios)

---

## Visão Geral

O SCED permite que organizações substituam planilhas manuais por um sistema centralizado de controle documental. Cada documento recebe um número de protocolo gerado automaticamente no formato `ANO-SEQUENCIAL` (ex: `2026-000001`), e todo o ciclo de vida do documento é rastreado com histórico completo de movimentações.

---

## Funcionalidades

- ✅ Autenticação de usuários com controle de sessão
- ✅ Controle de perfis: **Administrador**, **Supervisor N3** e **Operador**
- ✅ Registro de documentos com geração automática de protocolo
- ✅ Anexo de múltiplos arquivos por documento (PDF, DOC, DOCX, JPG, PNG)
- ✅ Consulta com filtros avançados (protocolo, remetente, tipo, status, período)
- ✅ Fluxo de status: **Novo → Em Análise → Pendente → Finalizado / Desativado**
- ✅ Histórico completo de movimentações por documento
- ✅ Geração de relatórios com exportação em PDF
- ✅ Log de auditoria de ações no sistema
- ✅ Interface responsiva para desktop e mobile

---

## Tecnologias Utilizadas

| Camada | Tecnologia |
|---|---|
| Back-end | Laravel 11 (PHP 8.2+) |
| Front-end | Blade + CSS customizado |
| Banco de Dados | MySQL 8 |
| Autenticação | Laravel Breeze |
| Relatórios PDF | barryvdh/laravel-dompdf |
| Build de assets | Vite + Tailwind CSS |
| Fontes | Google Fonts — Sora + JetBrains Mono |

---


## Arquitetura do Sistema

O sistema segue a arquitetura **MVC (Model-View-Controller)** do Laravel, com separação clara entre regras de negócio, apresentação e acesso a dados. A lógica de fluxo de documentos é centralizada em um Service (`ProcessoService`) e as permissões são gerenciadas por uma Policy (`ProcessoPolicy`).

```
sced/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProcessoController.php       ← CRUD + transições de status
│   │   │   ├── DashboardController.php
│   │   │   ├── UsuarioController.php
│   │   │   ├── TipoDocumentoController.php
│   │   │   ├── DepartamentoController.php
│   │   │   ├── RelatorioController.php
│   │   │   ├── LogAuditoriaController.php
│   │   │   └── Api/ServicoController.php    ← endpoints JSON
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── N3Middleware.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Documento.php
│   │   ├── TipoDocumento.php
│   │   ├── Departamento.php
│   │   ├── HistoricoMovimentacao.php
│   │   ├── ArquivoAnexo.php
│   │   └── LogAuditoria.php
│   ├── Services/
│   │   └── ProcessoService.php              ← regras de negócio do fluxo
│   ├── Policies/
│   │   └── ProcessoPolicy.php               ← controle de permissões
│   └── Exceptions/
│       └── StatusTransitionException.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── processos/
│   ├── usuarios/
│   ├── departamentos/
│   ├── admin/
│   │   ├── tipos/
│   │   └── logs/
│   └── relatorios/
├── public/css/
│   └── sced.css
└── routes/
    ├── web.php
    └── api.php
```

---

## Estrutura do Banco de Dados

O sistema utiliza 6 tabelas principais:

| Tabela | Descrição |
|---|---|
| `users` | Usuários do sistema com perfil e status |
| `departamentos` | Departamentos da organização |
| `tipo_documentos` | Categorias de documentos com SLA e cargo responsável |
| `documentos` | Registro principal dos documentos e seus metadados |
| `historico_movimentacoes` | Rastreamento de cada alteração de status |
| `arquivo_anexos` | Arquivos físicos vinculados a cada documento |
| `log_auditorias` | Registro de ações realizadas pelos usuários |

### Formato do protocolo

O número de protocolo é gerado automaticamente no formato:

```
ANO-SEQUENCIAL
Exemplo: 2026-000001
```

O sequencial é reiniciado a cada ano e possui 6 dígitos com zeros à esquerda.

---

## Perfis de Acesso

### 👑 Administrador
- Acesso completo ao sistema
- Gerencia usuários, departamentos e tipos de documento
- Acessa relatórios e exportação em PDF
- Pode alterar status de documentos manualmente
- Visualiza logs de auditoria

### 🔷 Supervisor N3
- Pode assumir, devolver, finalizar e reabrir processos
- Desativa processos e realiza alterações manuais de status
- Valida e rejeita anexos
- Visualiza logs de auditoria

### 👤 Operador
- Registra novos documentos
- Consulta e filtra documentos
- Assume processos disponíveis na fila
- Devolve processos ao solicitante com justificativa
- Substitui e reenvia anexos pendentes

---

## Telas do Sistema

| Tela | Descrição |
|---|---|
| Login | Autenticação com e-mail e senha |
| Dashboard | KPIs em tempo real, fila de atribuição e processos em aberto |
| Documentos | Listagem com filtros avançados e paginação |
| Novo Documento | Formulário com upload de múltiplos arquivos e autocomplete de tipo |
| Detalhes | Ficha completa, histórico de movimentações, validação de anexos e ações de fluxo |
| Tipos de Documento | Gerenciamento de categorias com SLA e cargo responsável |
| Departamentos | Gerenciamento de departamentos (somente admin) |
| Usuários | Gerenciamento de usuários (somente admin) |
| Relatórios | Geração de PDF filtrado por status, tipo e período |
| Logs de Auditoria | Histórico completo de ações no sistema (admin + N3) |

---

## Geração de Relatórios

Os relatórios podem ser filtrados por:

- Tipo de documento
- Status (Novo, Em Análise, Pendente, Finalizado, Desativado)
- Período (data inicial e data final)

O PDF gerado inclui:
- Cabeçalho institucional com data e usuário responsável
- Resumo numérico por status
- Tabela completa dos documentos filtrados
- Rodapé com identificação do sistema

---

## Licença

Este projeto foi desenvolvido para fins acadêmicos e organizacionais internos.

---

Desenvolvido por **Abner Cardoso, Guilherme Tófoli e Matheus Coronado**
