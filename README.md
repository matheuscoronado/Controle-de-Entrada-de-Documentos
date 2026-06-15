# 📂 SCED — Sistema de Controle de Entrada de Documentos

> Sistema web desenvolvido em Laravel para registrar, controlar e acompanhar a entrada, movimentação e validação de documentos dentro de uma organização, com fluxo hierárquico de aprovação.

---

## 📋 Índice

- [Visão Geral](#visão-geral)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Arquitetura do Sistema](#arquitetura-do-sistema)
- [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
- [Perfis de Acesso](#perfis-de-acesso)
- [Hierarquia e Fluxo de Processos](#hierarquia-e-fluxo-de-processos)
- [Telas do Sistema](#telas-do-sistema)
- [Geração de Relatórios](#geração-de-relatórios)
- [Instalação e Configuração](#instalação-e-configuração)

---

## Visão Geral

O SCED permite que organizações substituam planilhas manuais por um sistema centralizado de controle documental. Cada processo recebe um número de protocolo gerado automaticamente no formato `ANO-SEQUENCIAL` (ex: `2026-000001`), e todo o ciclo de vida do documento é rastreado com histórico completo de movimentações.

O sistema possui um **fluxo hierárquico de aprovação** onde:
- **N1 (Atendimento)** abre e acompanha processos
- **N2 (Analista)** analisa e valida documentos
- **N3 (Supervisor)** supervisiona e toma decisões finais
- **Administrador** tem acesso total ao sistema

---

## Funcionalidades

### Gestão de Processos
- ✅ Abertura de processos com seleção de serviço e anexo de documentos
- ✅ Geração automática de protocolo no formato `ANO-SEQUENCIAL`
- ✅ Upload múltiplo de arquivos (PDF, DOC, DOCX, JPG, PNG)
- ✅ Validação de documentos obrigatórios por tipo de serviço
- ✅ Aprovação e recusa de documentos com justificativa obrigatória
- ✅ Atribuição hierárquica de processos (N3 → N2 → N1)
- ✅ Assumir processos disponíveis na fila

### Fluxo de Status
- ✅ **Novo** → Processo aberto aguardando atribuição
- ✅ **Em Análise** → Processo sendo analisado pelo responsável
- ✅ **Pendente** → Aguardando correções do solicitante
- ✅ **Finalizado** → Processo concluído com sucesso
- ✅ **Desativado** → Processo cancelado/arquivado

### Rastreabilidade
- ✅ Histórico completo de movimentações com data, hora e usuário
- ✅ Log de auditoria de todas as ações do sistema
- ✅ Motivo obrigatório para devolução e recusa de documentos

### Interface
- ✅ Dashboard com KPIs e gráficos por perfil de usuário
- ✅ Listagem de processos com filtros avançados
- ✅ Contadores de processos pendentes (apenas ações que exigem intervenção)
- ✅ Layout responsivo para desktop e mobile
- ✅ Sidebar com informações do usuário (cargo e setor)
- ✅ Mensagens traduzidas para português (PT-BR)

### Administração
- ✅ Cadastro e gerenciamento de usuários com perfis e cargos
- ✅ Cadastro de departamentos e setores
- ✅ Cadastro de serviços com vinculação de documentos obrigatórios
- ✅ Cadastro de documentos (RG, CPF, etc.) para uso nos serviços
- ✅ Geração de relatórios com exportação em PDF
- ✅ Visualização de logs de auditoria

---

## Tecnologias Utilizadas

| Camada | Tecnologia |
|---|---|
| Back-end | Laravel 12 (PHP 8.2+) |
| Front-end | Blade + CSS customizado |
| Banco de Dados | MySQL 8 |
| Autenticação | Laravel Breeze |
| Relatórios PDF | barryvdh/laravel-dompdf |
| Gráficos | Chart.js |
| Ícones | Emojis nativos |
| Fontes | Google Fonts — Sora + JetBrains Mono |

---

## Arquitetura do Sistema

O sistema segue a arquitetura **MVC (Model-View-Controller)** do Laravel, com separação clara entre regras de negócio, apresentação e acesso a dados.

```
sced/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ProcessoController.php       ← CRUD + transições de status
│   │   │   ├── DashboardController.php      ← KPIs e gráficos
│   │   │   ├── UsuarioController.php        ← CRUD usuários
│   │   │   ├── TipoDocumentoController.php  ← CRUD serviços
│   │   │   ├── DocumentoTipoController.php  ← CRUD documentos
│   │   │   ├── DepartamentoController.php   ← CRUD departamentos
│   │   │   ├── RelatorioController.php      ← Geração PDF
│   │   │   ├── LogAuditoriaController.php   ← Logs de auditoria
│   │   │   └── Api/ServicoController.php    ← Endpoints JSON
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php          ← Apenas administradores
│   │       ├── N3Middleware.php             ← Apenas N3 e admin
│   │       └── Authenticate.php
│   ├── Models/
│   │   ├── User.php                  ← Usuários com perfis e cargos
│   │   ├── Documento.php             ← Processos/protocolos
│   │   ├── TipoDocumento.php         ← Serviços
│   │   ├── DocumentoTipo.php         ← Documentos cadastrados
│   │   ├── Departamento.php          ← Departamentos
│   │   ├── HistoricoMovimentacao.php ← Histórico de ações
│   │   ├── ArquivoAnexo.php          ← Arquivos anexados
│   │   └── LogAuditoria.php          ← Logs de auditoria
│   ├── Services/
│   │   └── ProcessoService.php       ← Regras de negócio do fluxo
│   ├── Policies/
│   │   └── ProcessoPolicy.php        ← Controle de permissões
│   └── Exceptions/
│       └── StatusTransitionException.php ← Exceções de transição
├── database/
│   ├── migrations/                   ← Estrutura do banco
│   └── seeders/                      ← Dados iniciais
├── resources/views/
│   ├── layouts/app.blade.php         ← Layout principal
│   ├── auth/login.blade.php          ← Login traduzido
│   ├── dashboard.blade.php           ← KPIs e gráficos
│   ├── processos/                    ← Telas de processos
│   ├── usuarios/                     ← CRUD usuários
│   ├── departamentos/                ← CRUD departamentos
│   ├── admin/                        ← Área administrativa
│   │   ├── tipos/                    ← CRUD serviços
│   │   ├── documentos/               ← CRUD documentos
│   │   └── logs/                     ← Logs de auditoria
│   └── relatorios/                   ← Geração de relatórios
├── public/css/
│   └── sced.css                      ← Estilos customizados
└── routes/
    ├── web.php                       ← Rotas web
    └── api.php                       ← Rotas API (JSON)
```

---

## Estrutura do Banco de Dados

O sistema utiliza as seguintes tabelas principais:

| Tabela | Descrição |
|---|---|
| `users` | Usuários com perfil, cargo, departamento e status |
| `departamentos` | Departamentos/Setores da organização |
| `tipo_documentos` | Serviços com cargos responsáveis e vinculação de documentos |
| `documento_tipos` | Documentos cadastrados (RG, CPF, etc.) |
| `tipo_documento_documento_tipo` | Tabela pivot (serviço ↔ documentos obrigatórios) |
| `documentos` | Processos com protocolo, status e responsável |
| `historico_movimentacoes` | Histórico completo de ações por processo |
| `arquivo_anexos` | Arquivos anexados com validação |
| `log_auditorias` | Log de auditoria de ações do sistema |

### Formato do protocolo

```
ANO-SEQUENCIAL (6 dígitos)
Exemplo: 2026-000001
```

O sequencial é reiniciado a cada ano.

---

## Perfis de Acesso

### 👑 Administrador
- Acesso total ao sistema
- Gerencia usuários, departamentos, serviços e documentos
- Acessa relatórios e exportação em PDF
- Pode alterar status de processos manualmente
- Visualiza logs de auditoria
- Pode atribuir, assumir, validar e finalizar qualquer processo

### ⭐ Supervisor N3
- Atribui processos para N2 ou N1 do mesmo setor
- Pode assumir, devolver, finalizar e reabrir processos
- Desativa processos
- Valida e recusa anexos de qualquer processo do setor
- Visualiza logs de auditoria

### 📊 Analista N2
- Atribui processos para N1 do mesmo setor
- Assume processos disponíveis na fila
- Analisa e valida documentos (quando responsável)
- Devolve processos ao solicitante com justificativa
- Finaliza processos (quando responsável)

### 🎯 Atendimento N1
- Abre novos processos
- Anexa documentos e seleciona tipos
- Acompanha processos que abriu
- Reenvia processos corrigidos
- Substitui anexos pendentes

---

## Hierarquia e Fluxo de Processos

### Fluxo Completo

```
N1 abre processo com documentos
        ↓
Processo fica NOVO no setor destino
        ↓
N3 atribui para N2 (ou N1) do setor
        OU
N2 assume diretamente da fila
        ↓
Responsável analisa documentos:
  - Aprova ✅ → documento validado
  - Recusa ❌ → solicita correção com motivo
        ↓
Se todos documentos aprovados:
  → Responsável FINALIZA processo

Se houver recusa:
  → Responsável DEVOLVE processo
  → Processo fica PENDENTE
  → Solicitante recebe notificação
        ↓
Solicitante corrige e REENVIA
        ↓
Processo retorna para o MESMO responsável
        ↓
Responsável continua análise
```

### Regras de Atribuição

| Quem | Pode atribuir para |
|------|-------------------|
| N3 | N2 ou N1 do mesmo setor |
| N2 | N1 do mesmo setor |
| N1 | Não pode atribuir |
| Admin | Qualquer usuário |

### Regras de Validação de Documentos

| Quem | Pode validar |
|------|-------------|
| Responsável atual | ✅ Sim (qualquer cargo) |
| Supervisor N3 | ✅ Sim (mesmo não sendo responsável) |
| Administrador | ✅ Sim |
| Outros usuários | ❌ Não |

---

## Telas do Sistema

| Tela | Descrição |
|---|---|
| **Login** | Autenticação com e-mail e senha (traduzida para PT-BR) |
| **Dashboard** | KPIs em tempo real, gráficos e processos recentes |
| **Processos** | Listagem com filtros, abas e contadores de pendências |
| **Novo Processo** | Seleção de serviço, documentos obrigatórios e upload de arquivos |
| **Detalhes do Processo** | Informações, documentos, histórico de ações e validação |
| **Cadastro de Serviços** | CRUD de serviços com vinculação de documentos obrigatórios |
| **Cadastro de Documentos** | CRUD de documentos (RG, CPF, etc.) |
| **Cadastro de Usuários** | CRUD com perfis, cargos e permissões |
| **Cadastro de Departamentos** | CRUD de setores da organização |
| **Relatórios** | Geração de PDF com filtros |
| **Logs de Auditoria** | Histórico completo de ações (admin + N3) |

---

## Geração de Relatórios

Os relatórios podem ser filtrados por:

- Serviço
- Status (Novo, Em Análise, Pendente, Finalizado, Desativado)
- Período (data de abertura)

O PDF gerado inclui:
- Cabeçalho institucional com data e usuário responsável
- Resumo numérico por status
- Tabela completa dos processos filtrados
- Rodapé com identificação do sistema

---

## Instalação e Configuração

### Pré-requisitos

- PHP 8.2+
- Composer
- MySQL 8+
- Node.js (para compilar assets)

### Passos para instalação

```bash
# 1. Clonar o repositório
git clone https://github.com/seu-usuario/sced.git
cd sced

# 2. Instalar dependências do PHP
composer install

# 3. Instalar dependências do Node
npm install

# 4. Copiar arquivo de ambiente
cp .env.example .env

# 5. Gerar chave da aplicação
php artisan key:generate

# 6. Configurar banco de dados no .env
# DB_DATABASE=sced
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Executar migrations e seeders
php artisan migrate --seed

# 8. Criar link simbólico para storage
php artisan storage:link

# 9. Compilar assets
npm run build

# 10. Iniciar servidor
php artisan serve
```

### Acessando o sistema

1. Após iniciar o servidor, acesse `http://localhost:8000` no navegador.
2. Você será redirecionado para a tela de **Login**.
3. Informe o e-mail e a senha de um usuário previamente cadastrado (criados via seeder ou diretamente no cadastro de usuários por um administrador).
4. Após autenticado, o sistema redireciona automaticamente para o **Dashboard**, exibindo os KPIs e funcionalidades de acordo com o perfil (N1, N2, N3 ou Administrador) do usuário logado.

> ⚠️ As credenciais de acesso (usuários e senhas) não são públicas e devem ser solicitadas ao administrador do sistema ou definidas localmente no ambiente de desenvolvimento via seeders.

---

## Licença

Este projeto foi desenvolvido para fins acadêmicos e organizacionais internos.

---

## Desenvolvedores

Desenvolvido por Abner Cardoso, Guilherme Tófoli e Matheus Coronado

SCED © 2026 — Sistema de Controle de Entrada de Documentos
