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
- ✅ Controle de perfis: **Administrador** e **Operador**
- ✅ Registro de documentos com geração automática de protocolo
- ✅ Anexo de múltiplos arquivos por documento (PDF, DOC, DOCX, JPG, PNG)
- ✅ Consulta com filtros avançados (protocolo, remetente, tipo, status, período)
- ✅ Controle de status: **Recebido → Em Análise → Encaminhado → Finalizado**
- ✅ Histórico completo de movimentações por documento
- ✅ Geração de relatórios com exportação em PDF
- ✅ Log de auditoria de ações no sistema
- ✅ Interface responsiva para desktop e mobile

---

## Tecnologias Utilizadas

| Camada | Tecnologia |
|---|---|
| Back-end | Laravel 11 (PHP 8.2+) |
| Front-end | Blade + CSS customizado + Bootstrap 5 |
| Banco de Dados | MySQL 8 |
| Autenticação | Laravel Breeze |
| Relatórios PDF | barryvdh/laravel-dompdf |
| Fontes | Google Fonts — Sora + JetBrains Mono |

---

## Arquitetura do Sistema

O sistema segue a arquitetura **MVC (Model-View-Controller)** do Laravel, com separação clara entre regras de negócio, apresentação e acesso a dados.

```
sced/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DocumentoController.php
│   │   │   ├── UsuarioController.php
│   │   │   ├── TipoDocumentoController.php
│   │   │   └── RelatorioController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Documento.php
│       ├── TipoDocumento.php
│       ├── HistoricoMovimentacao.php
│       ├── ArquivoAnexo.php
│       └── LogAuditoria.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── auth/login.blade.php
│   ├── dashboard.blade.php
│   ├── documentos/
│   ├── usuarios/
│   ├── tipos/
│   └── relatorios/
├── public/css/
│   └── sced.css
└── routes/
    └── web.php
```

---

## Estrutura do Banco de Dados

O sistema utiliza 6 tabelas principais:

| Tabela | Descrição |
|---|---|
| `users` | Usuários do sistema com perfil e status |
| `tipo_documentos` | Categorias de documentos (Ofício, Memorando, etc.) |
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
- Gerencia usuários (criar, editar, ativar/desativar)
- Gerencia tipos de documento
- Acessa relatórios e exportação em PDF
- Pode alterar status de documentos finalizados
- Visualiza logs de auditoria

### 👤 Operador
- Registra novos documentos
- Consulta e filtra documentos
- Atualiza status de documentos (exceto finalizados)
- Adiciona observações nas movimentações
- Faz download de arquivos anexos

---

## Telas do Sistema

| Tela | Descrição |
|---|---|
| Login | Autenticação com e-mail e senha |
| Dashboard | Visão geral com cards de estatísticas e documentos recentes |
| Documentos | Listagem com filtros avançados e paginação |
| Novo Documento | Formulário com upload de múltiplos arquivos |
| Detalhes | Ficha completa, histórico de movimentações e alteração de status |
| Tipos de Documento | Gerenciamento de categorias |
| Usuários | Gerenciamento de usuários (somente admin) |
| Relatórios | Geração de PDF filtrado por status, tipo e período |

---

## Geração de Relatórios

Os relatórios podem ser filtrados por:

- Tipo de documento
- Status (Recebido, Em Análise, Encaminhado, Finalizado)
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