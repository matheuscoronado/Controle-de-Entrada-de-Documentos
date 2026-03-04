# Sistema de Controle de Entrada de Documentos (SCED)

## 1. Visão Geral e Funcionalidades

O SCED (Sistema de Controle de Entrada de Documentos) é uma aplicação web desenvolvida para registrar, controlar e acompanhar a entrada e movimentação de documentos dentro de uma organização.

O sistema permite organizar documentos por tipo, acompanhar o status de tramitação e manter um histórico completo das alterações realizadas.

### Principais funcionalidades:

Autenticação de usuários com controle de sessão.

Controle de perfis (Administrador e Operador).

Registro de documentos com geração automática de protocolo.

Consulta de documentos utilizando filtros avançados.

Controle de status (Recebido, Em análise, Encaminhado e Finalizado).

Histórico de movimentações para auditoria.

Geração de relatórios com exportação em PDF ou Excel.

## 2. Arquitetura do Sistema

O sistema segue uma arquitetura web tradicional baseada em Back-end, Front-end e Banco de Dados relacional, garantindo organização das informações e controle das operações.

### Principais componentes: 

* Sistema Web	Interface para cadastro, 
* consulta e gerenciamento de documentos
* Banco de Dados Relacional	
* Armazenamento estruturado de usuários, documentos e históricos
* Controle de Sessão	
* Gerenciamento de login e autenticação
* Controle de Permissões	
* Restrição de funcionalidades conforme perfil do usuário

## 3. Funcionalidades Principais
Cadastro de Usuários
Permite criar e gerenciar usuários do sistema com dois perfis:
 - Administrador

> Gerencia usuários <p>
> Acessa todas as funcionalidades do sistema

- Operador

> Registra documentos <p>
> Consulta informações <p>
> Atualiza status <p>
> Registro de Documentos <p>
> Permite registrar a entrada de documentos no sistema.
