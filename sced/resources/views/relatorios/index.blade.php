{{-- ============================================================
     Arquivo: resources/views/relatorios/index.blade.php
     Formulário de geração de relatório
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Relatórios')
@section('subtitle', 'Gere relatórios filtrados e exporte em PDF')

@section('content')

<div class="row g-3">
    {{-- Formulário de filtros --}}
    <div class="col-12 col-lg-5">
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📊 Configurar Relatório
            </strong>
            <form method="POST" action="{{ route('relatorios.gerar') }}" target="_blank">
                @csrf

                <div class="form-group">
                    <label class="form-label-sced">Tipo de Documento</label>
                    <select name="tipo_documento_id" class="form-input-sced">
                        <option value="">Todos os tipos</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label-sced">Status</label>
                    <select name="status" class="form-input-sced">
                        <option value="">Todos os status</option>
                        <option value="recebido">📥 Recebido</option>
                        <option value="em_analise">🔍 Em Análise</option>
                        <option value="encaminhado">↗️ Encaminhado</option>
                        <option value="finalizado">✅ Finalizado</option>
                    </select>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label-sced">Data inicial</label>
                            <input type="date" name="data_inicio" class="form-input-sced">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label-sced">Data final</label>
                            <input type="date" name="data_fim" class="form-input-sced"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div style="padding-top:20px; border-top:1px solid var(--cinza-200);">
                    <button type="submit" class="btn-primary-sced" style="width:100%; justify-content:center; font-size:15px; padding:13px;">
                        📥 Gerar e Baixar PDF
                    </button>
                    <div style="text-align:center; font-size:12px; color:var(--cinza-400); margin-top:8px;">
                        O relatório abrirá em nova aba para download.
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Prévia de estatísticas --}}
    <div class="col-12 col-lg-7">
        <div class="card-sced card-body-sced">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📈 Visão Geral do Acervo
            </strong>
            <div class="row g-3">
                @php
                    $statusConfig = [
                        'recebido'    => ['label'=>'Recebido',    'icon'=>'📥', 'color'=>'#2563eb'],
                        'em_analise'  => ['label'=>'Em Análise',  'icon'=>'🔍', 'color'=>'#d97706'],
                        'encaminhado' => ['label'=>'Encaminhado', 'icon'=>'↗️', 'color'=>'#0891b2'],
                        'finalizado'  => ['label'=>'Finalizado',  'icon'=>'✅', 'color'=>'#059669'],
                    ];
                    $totalGeral = \App\Models\Documento::count();
                @endphp
                @foreach($statusConfig as $key => $cfg)
                @php
                    $qtd = \App\Models\Documento::where('status', $key)->count();
                @endphp
                <div class="col-6">
                    <div style="background:var(--cinza-100); border-radius:var(--radius-sm); padding:16px; display:flex; align-items:center; gap:12px;">
                        <span style="font-size:24px;">{{ $cfg['icon'] }}</span>
                        <div>
                            <div class="status-count" data-color="{{ $cfg['color'] }}" style="font-size:22px; font-weight:700;">{{ $qtd }}</div>
                            <div style="font-size:12px; color:var(--cinza-600);">{{ $cfg['label'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--cinza-200);">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:14px; color:var(--cinza-600);">Total geral de documentos</span>
                    <span style="font-size:24px; font-weight:700; color:var(--azul-escuro);">{{ $totalGeral }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-count[data-color]').forEach(function (el) {
            el.style.color = el.dataset.color;
        });
    });
</script>
@endpush
