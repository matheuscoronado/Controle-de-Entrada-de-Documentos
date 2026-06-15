<?php
// app/Policies/ProcessoPolicy.php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;

class ProcessoPolicy
{
    // ── Regras gerais ────────────────────────────────────────

    /** Admin passa em todas as verificações */
    public function before(User $user, string $ability): ?bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }
        
        return null;
    }

    // ── Visualização ─────────────────────────────────────────

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Documento $doc): bool
    {
        return true;
    }

    // ── Criação ──────────────────────────────────────────────

    public function create(User $user): bool
    {
        return true;
    }

    // ── Edição de dados ──────────────────────────────────────

    public function update(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        $editaveisOperador = ['novo', 'pendente'];

        return $doc->usuario_registro_id === $user->id
            && in_array($doc->status, $editaveisOperador);
    }

    // ── Assumir processo ─────────────────────────────────────

    public function assumir(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        if (!$user->podeAssumirProcesso()) {
            return false;
        }

        if (!in_array($doc->status, ['novo', 'pendente'])) {
            return false;
        }

        if ($doc->atribuido_a_id && $doc->atribuido_a_id !== $user->id) {
            return false;
        }

        $depDestino = $doc->departamento_destino_id
                    ?? optional($doc->tipoDocumento)->departamento_destino_id;

        if ($depDestino && (int)$user->departamento_id !== (int)$depDestino) {
            return false;
        }

        return true;
    }

    // ── ATRIBUIR PROCESSO (N3 para N2, N2 para N1) ───────────

    public function atribuir(User $user, Documento $doc): bool
    {
        // Admin pode atribuir qualquer processo (já tratado pelo before)
        
        // N1 não pode atribuir
        if ($user->cargo == 'N1') {
            return false;
        }
        
        // Verifica se o processo está em aberto
        if (!in_array($doc->status, ['novo', 'em_analise'])) {
            return false;
        }
        
        // Verifica se o usuário pertence ao setor do processo
        if ($user->departamento_id != $doc->departamento_destino_id) {
            return false;
        }
        
        // N3 pode atribuir para N2 ou N1
        if ($user->cargo == 'N3') {
            return true;
        }
        
        // N2 pode atribuir para N1
        if ($user->cargo == 'N2') {
            return true;
        }
        
        return false;
    }

    // ── Devolver ao solicitante ─────────────────────────────

    public function devolver(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'em_analise'
            && $doc->atribuido_a_id === $user->id;
    }

    // ── Retornar ao analista ────────────────────────────────

    public function retornar(User $user, Documento $doc): bool
    {
        return $doc->status === 'pendente'
            && $doc->usuario_registro_id === $user->id;
    }

    // ── Finalizar ────────────────────────────────────────────

    public function finalizar(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'em_analise'
            && $doc->atribuido_a_id === $user->id;
    }

    // ── Desativar ────────────────────────────────────────────

    public function desativar(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3() && $doc->status !== 'desativado';
    }

    // ── Reabrir ──────────────────────────────────────────────

    public function reabrir(User $user, Documento $doc): bool
    {
        return method_exists($user, 'isN3') && $user->isN3()
            && in_array($doc->status, ['finalizado', 'desativado']);
    }

    // ── Alteração manual de status ──────────────────────────

    public function alterarStatusManual(User $user, Documento $doc): bool
    {
        return false;
    }

    // ── Substituir anexos ────────────────────────────────────

    public function substituirAnexo(User $user, Documento $doc): bool
    {
        if (method_exists($user, 'isN3') && $user->isN3()) return true;

        return $doc->status === 'pendente'
            && $doc->usuario_registro_id === $user->id;
    }

    // ⭐ ── VALIDAR ANEXOS (CORRIGIDO) ─────────────────────────

    public function validarAnexo(User $user, Documento $doc): bool
    {
        // N3 pode validar anexos
        if (method_exists($user, 'isN3') && $user->isN3()) {
            return true;
        }
        
        // Responsável atual do processo pode validar anexos
        if ($doc->atribuido_a_id === $user->id && $doc->status === 'em_analise') {
            return true;
        }
        
        return false;
    }
}