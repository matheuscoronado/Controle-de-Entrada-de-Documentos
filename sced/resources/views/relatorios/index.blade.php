{{-- ============================================================
     resources/views/relatorios/index.blade.php
     TELA DE RELATÓRIOS - GERAÇÃO DE RELATÓRIOS EM PDF
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Relatórios')
@section('subtitle', 'Geração de relatórios do sistema')

@section('content')

<style>
    /* Cards */
    .relatorio-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }
    .relatorio-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--sombra-hover);
    }
    .relatorio-header {
        padding: 20px;
        background: linear-gradient(135deg, var(--azul-escuro) 0%, var(--azul-medio) 100%);
        color: white;
    }
    .relatorio-header .icon {
        font-size: 32px;
        margin-bottom: 12px;
    }
    .relatorio-header h3 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }
    .relatorio-body {
        padding: 20px;
    }
    .relatorio-desc {
        font-size: 13px;
        color: var(--cinza-600);
        margin-bottom: 20px;
        line-height: 1.5;
    }
    
    /* Formulário principal */
    .form-card {
        background: var(--branco);
        border-radius: 16px;
        border: 1px solid var(--cinza-200);
        padding: 28px;
        margin-bottom: 32px;
    }
    .form-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--azul-escuro);
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--cinza-200);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--cinza-600);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    .form-input {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid var(--cinza-200);
        border-radius: 10px;
        font-family: 'Sora', sans-serif;
        font-size: 14px;
        transition: all 0.2s;
    }
    .form-input:focus {
        border-color: var(--azul-claro);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }
    
    /* Botões */
    .btn-relatorio {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        width: 100%;
        justify-content: center;
    }
    .btn-pdf {
        background: #dc2626;
        color: white;
    }
    .btn-pdf:hover {
        background: #b91c1c;
        transform: translateY(-1px);
    }
    .btn-excel {
        background: #059669;
        color: white;
    }
    .btn-excel:hover {
        background: #047857;
        transform: translateY(-1px);
    }
    
    /* Grid de relatórios rápidos */
    .rapidos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-top: 32px;
    }
    
    /* Badge de status */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin: 2px;
    }
    .status-novo { background: #eff6ff; color: #2563eb; }
    .status-em_analise { background: #fffbeb; color: #d97706; }
    .status-pendente { background: #fef3c7; color: #92400e; }
    .status-finalizado { background: #f0fdf4; color: #059669; }
    .status-desativado { background: #f1f5f9; color: #64748b; }
    
    /* Legenda */
    .legenda-card {
        background: var(--cinza-100);
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
    }
    .legenda-title {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--cinza-400);
        margin-bottom: 12px;
    }
    .legenda-items {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
</style>

<div class="row g-4">
    
    {{-- COLUNA DO FORMULÁRIO PRINCIPAL --}}
    <div class="col-lg-6">
        <div class="form-card">
            <div class="form-title">
                📊 Gerar Relatório Personalizado
            </div>

            <form method="POST" action="{{ route('relatorios.gerar') }}" target="_blank">
                @csrf

                <div class="form-group">
                    <label class="form-label">Período</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="date" name="data_inicio" class="form-input" 
                                   value="{{ date('Y-m-01') }}" placeholder="Data Inicial">
                        </div>
                        <div class="col-6">
                            <input type="date" name="data_fim" class="form-input" 
                                   value="{{ date('Y-m-d') }}" placeholder="Data Final">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Serviço</label>
                    <select name="tipo_documento_id" class="form-input">
                        <option value="">Todos os serviços</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Todos os status</option>
                        <option value="novo">🆕 Novo</option>
                        <option value="em_analise">🔍 Em Análise</option>
                        <option value="pendente">⏳ Pendente</option>
                        <option value="finalizado">✅ Finalizado</option>
                        <option value="desativado">🚫 Desativado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Formato do Relatório</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <button type="submit" name="formato" value="pdf" class="btn-relatorio btn-pdf">
                                📄 Gerar PDF
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn-relatorio btn-excel" disabled style="opacity: 0.5;">
                                📊 Excel (Em breve)
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="legenda-card">
                <div class="legenda-title">📋 Legenda de Status</div>
                <div class="legenda-items">
                    <span class="status-badge status-novo">🆕 Novo</span>
                    <span class="status-badge status-em_analise">🔍 Em Análise</span>
                    <span class="status-badge status-pendente">⏳ Pendente</span>
                    <span class="status-badge status-finalizado">✅ Finalizado</span>
                    <span class="status-badge status-desativado">🚫 Desativado</span>
                </div>
            </div>
        </div>
    </div>

    {{-- COLUNA DE INFORMAÇÕES --}}
    <div class="col-lg-6">
        <div class="form-card">
            <div class="form-title">
                ℹ️ Sobre os Relatórios
            </div>
            <div style="margin-bottom: 20px;">
                <p style="font-size: 14px; color: var(--cinza-600); line-height: 1.6;">
                    Os relatórios são gerados em <strong>formato PDF</strong> e incluem todas as informações 
                    dos processos conforme os filtros selecionados.
                </p>
                <p style="font-size: 14px; color: var(--cinza-600); line-height: 1.6; margin-top: 12px;">
                    <strong>O que é incluído no relatório:</strong>
                </p>
                <ul style="margin-top: 8px; padding-left: 20px;">
                    <li style="font-size: 13px; color: var(--cinza-600);">📋 Número do protocolo</li>
                    <li style="font-size: 13px; color: var(--cinza-600);">🏷️ Tipo de serviço</li>
                    <li style="font-size: 13px; color: var(--cinza-600);">👤 Solicitante</li>
                    <li style="font-size: 13px; color: var(--cinza-600);">📅 Data de abertura</li>
                    <li style="font-size: 13px; color: var(--cinza-600);">📌 Status atual</li>
                    <li style="font-size: 13px; color: var(--cinza-600);">📎 Quantidade de anexos</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- RELATÓRIOS RÁPIDOS --}}
<div class="rapidos-grid">
    <div class="relatorio-card">
        <div class="relatorio-header">
            <div class="icon">📋</div>
            <h3>Processos do Mês</h3>
        </div>
        <div class="relatorio-body">
            <div class="relatorio-desc">
                Gera um relatório com todos os processos abertos no mês atual.
            </div>
            <form method="POST" action="{{ route('relatorios.gerar') }}" target="_blank">
                @csrf
                <input type="hidden" name="data_inicio" value="{{ date('Y-m-01') }}">
                <input type="hidden" name="data_fim" value="{{ date('Y-m-d') }}">
                <button type="submit" name="formato" value="pdf" class="btn-relatorio btn-pdf" style="background: var(--azul-claro);">
                    📄 Gerar Relatório
                </button>
            </form>
        </div>
    </div>

    <div class="relatorio-card">
        <div class="relatorio-header">
            <div class="icon">✅</div>
            <h3>Processos Finalizados</h3>
        </div>
        <div class="relatorio-body">
            <div class="relatorio-desc">
                Gera um relatório com todos os processos finalizados no período.
            </div>
            <form method="POST" action="{{ route('relatorios.gerar') }}" target="_blank">
                @csrf
                <input type="hidden" name="status" value="finalizado">
                <input type="hidden" name="data_inicio" value="{{ date('Y-m-01') }}">
                <input type="hidden" name="data_fim" value="{{ date('Y-m-d') }}">
                <button type="submit" name="formato" value="pdf" class="btn-relatorio btn-pdf" style="background: var(--verde);">
                    📄 Gerar Relatório
                </button>
            </form>
        </div>
    </div>

    <div class="relatorio-card">
        <div class="relatorio-header">
            <div class="icon">⏳</div>
            <h3>Processos Pendentes</h3>
        </div>
        <div class="relatorio-body">
            <div class="relatorio-desc">
                Gera um relatório com todos os processos pendentes de análise.
            </div>
            <form method="POST" action="{{ route('relatorios.gerar') }}" target="_blank">
                @csrf
                <input type="hidden" name="status" value="pendente">
                <button type="submit" name="formato" value="pdf" class="btn-relatorio btn-pdf" style="background: var(--amarelo);">
                    📄 Gerar Relatório
                </button>
            </form>
        </div>
    </div>
</div>

@endsection