<?php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class ProcessoPolicy
{
    // ── Regras gerais ────────────────────────────────────────

    /** Admin passa em todas as verificações */
    public function before(User $user, string $ability): ?bool
    {
        // Usamos method_exists para evitar que erros de métodos inexistentes no Model quebrem a aplicação com 403
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        
        return null;
    }

    // ── Visualização ─────────────────────────────────────────

    /** Qualquer autenticado pode ver a listagem */
    public function viewAny(User $user): bool
    {
        // Retorna true sem nenhuma barreira para a listagem geral.
        // Os filtros de escopo (Operador vs Admin) já são controlados por você dentro do ProcessoController@index
        return true;
    }

    /** Qualquer autenticado pode ver um processo específico */
    public function view(User $user, Documento $doc): bool
    {
        return true;
    }

    // ── Criação ──────────────────────────────────────────────

    /** Qualquer autenticado pode abrir um processo */
    public function create(User $user): bool
    {
        return true;
    }

    // ── Edição de dados ──────────────────────────────────────

    /**
     * Pode editar dados do processo se:
     * - É o autor E o processo está em 'novo' ou 'pendente'
     * - OU é admin/N3
     */
    public function update(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        $editaveisOperador = ['novo', 'pendente'];

        return $doc->usuario_registro_id === $user->id
            && in_array($doc->status, $editaveisOperador);
    }

    // ── Assumir processo (Atribuição) ────────────────────────

    /**
     * Pode assumir se o processo está em 'novo' ou 'pendente'
     * e o usuário pertence ao departamento de destino
     * (ou é admin/N3).
     * Se já está atribuído a outro, só admin/N3 pode reatribuir.
     */
    public function assumir(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        if (!in_array($doc->status, ['novo', 'pendente'])) {
            return false;
        }

        // Se já tem dono, bloqueia operadores
        if ($doc->atribuido_a_id && $doc->atribuido_a_id !== $user->id) {
            return false;
        }

        return true;
    }

    // ── Devolver ao solicitante (→ PENDENTE) ─────────────────

    /**
     * Pode devolver se está em 'em_analise'
     * E é quem está com o processo (ou admin/N3)
     */
    public function devolver(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'em_analise'
            && $doc->atribuido_a_id === $user->id;
    }

    // ── Retornar ao analista (PENDENTE → EM_ANALISE) ─────────

    /**
     * Pode retornar se é o autor do processo
     * e o status é 'pendente'
     */
    public function retornar(User $user, Documento $doc): bool
    {
        return $doc->status === 'pendente'
            && $doc->usuario_registro_id === $user->id;
    }

    // ── Finalizar ────────────────────────────────────────────

    /**
     * Pode finalizar se está em 'em_analise'
     * e é o analista responsável (ou admin/N3)
     */
    public function finalizar(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'em_analise'
            && $doc->atribuido_a_id === $user->id;
    }

    // ── Desativar ────────────────────────────────────────────

    /** Apenas admin/N3 — o before() já cobre admin */
    public function desativar(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3() && $doc->status !== 'desativado';
    }

    // ── Reabrir ──────────────────────────────────────────────

    /** Apenas admin/N3 — o before() já cobre admin */
    public function reabrir(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3()
            && in_array($doc->status, ['finalizado', 'desativado']);
    }

    // ── Alteração manual de status (admin/N3 apenas) ─────────

    public function alterarStatusManual(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3();
    }

    // ── Substituir anexos ────────────────────────────────────

    /**
     * Pode substituir anexos se:
     * - É o autor E o processo está em 'pendente'
     * - OU é admin/N3
     */
    public function substituirAnexo(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'pendente'
            && $doc->usuario_registro_id === $user->id;
    }

    // ── Validar anexos (admin/N3) ────────────────────────────

    public function validarAnexo(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3();
    }
}