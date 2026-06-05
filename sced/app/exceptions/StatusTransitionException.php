<?php
// app/Exceptions/StatusTransitionException.php
// ============================================================
// Exceção de domínio lançada pelo ProcessoService quando uma
// transição de status é inválida ou não permitida.
// O handler HTTP a converte em resposta 422/403 automaticamente.
// ============================================================

namespace App\Exceptions;

use RuntimeException;

class StatusTransitionException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly string $statusAtual  = '',
        public readonly string $statusAlvo   = '',
        public readonly int    $httpCode     = 422,
    ) {
        parent::__construct($message);
    }

    /** Atalho para bloqueios de permissão */
    public static function semPermissao(string $acao): self
    {
        return new self(
            message:    "Você não tem permissão para {$acao}.",
            httpCode:   403,
        );
    }

    /** Atalho para transição proibida pela máquina de estados */
    public static function transicaoInvalida(string $de, string $para): self
    {
        return new self(
            message:    "Não é possível mover o processo de '{$de}' para '{$para}'.",
            statusAtual: $de,
            statusAlvo:  $para,
            httpCode:   422,
        );
    }
}
