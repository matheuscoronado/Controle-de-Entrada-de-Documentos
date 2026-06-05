<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
        'numero_protocolo','tipo_documento_id','usuario_registro_id',
        'remetente','assunto','descricao','setor_destino','departamento_destino_id',
        'status','data_recebimento',
        'atribuido_a_id','atribuido_em','motivo_pendencia','motivo_desativacao','reaberto_em',
    ];

    protected $casts = ['atribuido_em'=>'datetime','reaberto_em'=>'datetime'];

    public const STATUS = [
        'novo'=>'Novo','em_analise'=>'Em Análise','pendente'=>'Pendente',
        'finalizado'=>'Finalizado','desativado'=>'Desativado',
    ];
    public const STATUS_CORES = [
        'novo'       => ['bg'=>'#eff6ff','color'=>'#2563eb'],
        'em_analise' => ['bg'=>'#fffbeb','color'=>'#d97706'],
        'pendente'   => ['bg'=>'#fef3c7','color'=>'#92400e'],
        'finalizado' => ['bg'=>'#f0fdf4','color'=>'#059669'],
        'desativado' => ['bg'=>'#f1f5f9','color'=>'#64748b'],
    ];

    public function tipoDocumento()       { return $this->belongsTo(TipoDocumento::class); }
    public function usuarioRegistro()     { return $this->belongsTo(User::class, 'usuario_registro_id'); }
    public function atribuidoA()          { return $this->belongsTo(User::class, 'atribuido_a_id'); }
    public function departamentoDestino() { return $this->belongsTo(Departamento::class, 'departamento_destino_id'); }
    public function historicos()          { return $this->hasMany(HistoricoMovimentacao::class)->orderBy('data_hora','desc'); }
    public function anexos()              { return $this->hasMany(ArquivoAnexo::class); }

    public function getLabelStatusAttribute(): string { return self::STATUS[$this->status] ?? $this->status; }
    public function isNovo(): bool       { return $this->status === 'novo'; }
    public function isEmAnalise(): bool  { return $this->status === 'em_analise'; }
    public function isPendente(): bool   { return $this->status === 'pendente'; }
    public function isFinalizado(): bool { return $this->status === 'finalizado'; }
    public function isDesativado(): bool { return $this->status === 'desativado'; }
    public function isEditavel(): bool   { return in_array($this->status, ['novo','pendente']); }

    public static function gerarProtocolo(): string
    {
        $ano    = date('Y');
        $ultimo = self::whereYear('created_at', $ano)->count();
        return $ano.'-'.str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);
    }
}
