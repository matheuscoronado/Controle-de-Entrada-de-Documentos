{{-- ============================================================
     Arquivo: resources/views/dashboard.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Dashboard')
@section('subtitle', 'Visão geral do sistema')

@section('topbar-actions')
    <a href="{{ route('documentos.create') }}" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
@endsection

@section('content')

{{-- Cards de estatísticas --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon azul">📄</div>
            <div>
                <div class="stat-valor">{{ $total }}</div>
                <div class="stat-label">Total de Documentos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon amarel">🔍</div>
            <div>
                <div class="stat-valor">{{ $porStatus['em_analise'] ?? 0 }}</div>
                <div class="stat-label">Em Análise</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon ciano">↗️</div>
            <div>
                <div class="stat-valor">{{ $porStatus['encaminhado'] ?? 0 }}</div>
                <div class="stat-label">Encaminhados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon verde">✅</div>
            <div>
                <div class="stat-valor">{{ $porStatus['finalizado'] ?? 0 }}</div>
                <div class="stat-label">Finalizados</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Documentos recentes --}}
    <div class="col-lg-8">
        <div class="card-sced">
            <div class="card-header-sced" style="padding-bottom:16px;">
                <strong style="font-size:15px; color:var(--azul-escuro);">📋 Documentos Recentes</strong>
                <a href="{{ route('documentos.index') }}" class="btn-outline-sced" style="font-size:12px;">
                    Ver todos →
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="tabela-sced">
                    <thead>
                        <tr>
                            <th>Protocolo</th>
                            <th>Assunto</th>
                            <th>Remetente</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentes ?? [] as $doc)
                        <tr>
                            <td><span class="protocolo-codigo">{{ $doc->numero_protocolo }}</span></td>
                            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $doc->assunto }}
                            </td>
                            <td>{{ $doc->remetente }}</td>
                            <td>
                                <span class="badge-status badge-{{ $doc->status }}">
                                    {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--cinza-400); padding:32px;">
                                Nenhum documento registrado ainda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Distribuição por status --}}
    <div class="col-lg-4">
        <div class="card-sced card-body-sced" style="padding:24px;">
            <strong style="font-size:15px; color:var(--azul-escuro); display:block; margin-bottom:20px;">
                📊 Por Status
            </strong>

            @php
                $statusConfig = [
                    'recebido'    => ['label' => 'Recebido',    'color' => '#2563eb'],
                    'em_analise'  => ['label' => 'Em Análise',  'color' => '#d97706'],
                    'encaminhado' => ['label' => 'Encaminhado', 'color' => '#0891b2'],
                    'finalizado'  => ['label' => 'Finalizado',  'color' => '#059669'],
                ];
                $totalLocal = max($total, 1);
            @endphp

            @foreach($statusConfig as $key => $cfg)
            @php $qtd = $porStatus[$key] ?? 0; $pct = round(($qtd / $totalLocal) * 100); @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px;">
                    <span style="color:var(--cinza-600); font-weight:500;">{{ $cfg['label'] }}</span>
                    <span style="font-weight:700; color:var(--cinza-800);">{{ $qtd }}</span>
                </div>
                <div style="background:var(--cinza-200); border-radius:10px; height:8px; overflow:hidden;">
                    <div style="height:100%; width:{{ $pct }}%; background:{{ $cfg['color'] }}; border-radius:10px; transition:width 0.5s ease;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Atualiza o dashboard a cada 60 segundos
// setTimeout(() => location.reload(), 60000);
</script>
@endpush
