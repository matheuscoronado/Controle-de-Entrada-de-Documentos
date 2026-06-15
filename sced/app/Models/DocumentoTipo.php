<?php
// app/Models/DocumentoTipo.php
// Representa um tipo de documento cadastrado (ex: RG, CPF, Certidão de Casamento)
// Diferente de TipoDocumento (que é o "Serviço").

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentoTipo extends Model
{
    use HasFactory;

    protected $table = 'documento_tipos';

    protected $fillable = ['nome', 'descricao', 'tipo', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    // Um DocumentoTipo pode estar vinculado a muitos Serviços (TipoDocumento) via pivot
    public function servicos()
    {
        return $this->belongsToMany(TipoDocumento::class, 'tipo_documento_documento_tipo', 'documento_tipo_id', 'tipo_documento_id');
    }

    public function getLabelTipoAttribute(): string
    {
        return $this->tipo === 'obrigatorio' ? 'Obrigatório' : 'Opcional';
    }

    /**
     * Escopo para buscar apenas documentos ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Escopo para buscar apenas documentos obrigatórios
     */
    public function scopeObrigatorio($query)
    {
        return $query->where('tipo', 'obrigatorio');
    }

    /**
     * Escopo para buscar apenas documentos opcionais
     */
    public function scopeOpcional($query)
    {
        return $query->where('tipo', 'opcional');
    }

    /**
     * Verifica se o documento está ativo
     */
    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }

    /**
     * Verifica se o documento é obrigatório
     */
    public function isObrigatorio(): bool
    {
        return $this->tipo === 'obrigatorio';
    }

    /**
     * Verifica se o documento é opcional
     */
    public function isOpcional(): bool
    {
        return $this->tipo === 'opcional';
    }
}