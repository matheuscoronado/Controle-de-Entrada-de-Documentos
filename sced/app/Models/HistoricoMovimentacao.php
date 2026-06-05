<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoMovimentacao extends Model
{
    protected $table    = 'historico_movimentacoes';
    public    $timestamps = false;

    protected $fillable = [
        'documento_id','usuario_id','usuario_destino_id',
        'tipo','status_anterior','status_novo','observacoes','data_hora',
    ];

    protected $casts = ['data_hora'=>'datetime'];

    public const TIPOS = [
        'criacao'            => ['label'=>'Processo criado',           'icone'=>'🆕'],
        'atribuicao'         => ['label'=>'Processo assumido',         'icone'=>'👤'],
        'devolucao'          => ['label'=>'Devolvido ao solicitante',  'icone'=>'↩️'],
        'retorno'            => ['label'=>'Reenviado pelo solicitante','icone'=>'🔄'],
        'finalizacao'        => ['label'=>'Processo finalizado',       'icone'=>'✅'],
        'edicao_dados'       => ['label'=>'Dados editados',            'icone'=>'✏️'],
        'substituicao_anexo' => ['label'=>'Anexo substituído',         'icone'=>'📎'],
        'desativacao'        => ['label'=>'Processo desativado',       'icone'=>'🚫'],
        'reabertura'         => ['label'=>'Processo reaberto',         'icone'=>'🔓'],
        'alteracao_manual'   => ['label'=>'Alteração manual',          'icone'=>'⚙️'],
    ];

    public function getLabelTipoAttribute(): string { return self::TIPOS[$this->tipo]['label'] ?? $this->tipo; }
    public function getIconeTipoAttribute(): string { return self::TIPOS[$this->tipo]['icone'] ?? '•'; }

    public function documento()     { return $this->belongsTo(Documento::class); }
    public function usuario()       { return $this->belongsTo(User::class, 'usuario_id'); }
    public function usuarioDestino(){ return $this->belongsTo(User::class, 'usuario_destino_id'); }
}
