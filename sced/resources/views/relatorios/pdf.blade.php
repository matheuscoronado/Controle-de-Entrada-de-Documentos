{{-- ============================================================
     Arquivo: resources/views/relatorios/pdf.blade.php
     Template do PDF gerado pelo DomPDF — NÃO usa o layout principal
     ============================================================ --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório SCED</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
        }

        /* Cabeçalho */
        .header {
            background: #0f2744;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header .sub {
            font-size: 10px;
            color: rgba(255,255,255,0.6);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-info {
            text-align: right;
            font-size: 10px;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }

        /* Filtros aplicados */
        .filtros {
            margin: 0 24px 16px;
            padding: 10px 14px;
            background: #f1f5f9;
            border-radius: 6px;
            border-left: 3px solid #2563eb;
        }

        .filtros-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .filtros-linha {
            font-size: 10px;
            color: #475569;
        }

        /* Resumo numérico */
        .resumo {
            display: flex;
            gap: 12px;
            margin: 0 24px 20px;
        }

        .resumo-item {
            flex: 1;
            text-align: center;
            padding: 10px 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .resumo-valor {
            font-size: 20px;
            font-weight: bold;
            line-height: 1;
        }

        .resumo-label {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tabela */
        .tabela-container {
            margin: 0 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #1a3f6f;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: bold;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .protocolo {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #2563eb;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-recebido    { background: #eff6ff; color: #2563eb; }
        .badge-em_analise  { background: #fffbeb; color: #d97706; }
        .badge-encaminhado { background: #ecfeff; color: #0891b2; }
        .badge-finalizado  { background: #f0fdf4; color: #059669; }

        /* Rodapé */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0; right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            padding: 8px 24px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        .sem-dados {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-size: 13px;
        }
    </style>
</head>
<body>

{{-- Cabeçalho --}}
<div class="header">
    <div class="header-top">
        <div>
            <h1>📂 SCED</h1>
            <div class="sub">Relatório de Documentos</div>
        </div>
        <div class="header-info">
            Gerado em: {{ now()->format('d/m/Y \à\s H:i') }}<br>
            Usuário: {{ auth()->user()->nome }}<br>
            Total de registros: {{ $documentos->count() }}
        </div>
    </div>
</div>

{{-- Filtros Aplicados --}}
<div class="filtros">
    <div class="filtros-title">Filtros aplicados</div>
    <div class="filtros-linha">
        @if($request->status)
            Status: <strong>{{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$request->status] }}</strong> &nbsp;|&nbsp;
        @endif
        @if($request->tipo_documento_id)
            Tipo: <strong>{{ \App\Models\TipoDocumento::find($request->tipo_documento_id)?->nome }}</strong> &nbsp;|&nbsp;
        @endif
        @if($request->data_inicio && $request->data_fim)
            Período: <strong>{{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }}</strong>
            até <strong>{{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }}</strong>
        @endif
        @if(!$request->status && !$request->tipo_documento_id && !$request->data_inicio)
            Sem filtros — todos os documentos
        @endif
    </div>
</div>

{{-- Resumo --}}
@php
    $contadores = $documentos->groupBy('status')->map->count();
@endphp
<div class="resumo">
    <div class="resumo-item" style="border-color:#bfdbfe;">
        <div class="resumo-valor" style="color:#2563eb;">{{ $contadores['recebido'] ?? 0 }}</div>
        <div class="resumo-label">Recebidos</div>
    </div>
    <div class="resumo-item" style="border-color:#fde68a;">
        <div class="resumo-valor" style="color:#d97706;">{{ $contadores['em_analise'] ?? 0 }}</div>
        <div class="resumo-label">Em Análise</div>
    </div>
    <div class="resumo-item" style="border-color:#a5f3fc;">
        <div class="resumo-valor" style="color:#0891b2;">{{ $contadores['encaminhado'] ?? 0 }}</div>
        <div class="resumo-label">Encaminhados</div>
    </div>
    <div class="resumo-item" style="border-color:#bbf7d0;">
        <div class="resumo-valor" style="color:#059669;">{{ $contadores['finalizado'] ?? 0 }}</div>
        <div class="resumo-label">Finalizados</div>
    </div>
    <div class="resumo-item" style="border-color:#cbd5e1; background:#f1f5f9;">
        <div class="resumo-valor" style="color:#1e293b;">{{ $documentos->count() }}</div>
        <div class="resumo-label">Total</div>
    </div>
</div>

{{-- Tabela de documentos --}}
<div class="tabela-container">
    @if($documentos->isEmpty())
        <div class="sem-dados">Nenhum documento encontrado com os filtros informados.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Tipo</th>
                <th>Assunto</th>
                <th>Remetente</th>
                <th>Destino</th>
                <th>Recebido em</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td><span class="protocolo">{{ $doc->numero_protocolo }}</span></td>
                <td>{{ $doc->tipoDocumento->nome }}</td>
                <td>{{ \Illuminate\Support\Str::limit($doc->assunto, 40) }}</td>
                <td>{{ $doc->remetente }}</td>
                <td>{{ $doc->setor_destino }}</td>
                <td>{{ \Carbon\Carbon::parse($doc->data_recebimento)->format('d/m/Y') }}</td>
                <td>
                    <span class="badge badge-{{ $doc->status }}">
                        {{ ['recebido'=>'Recebido','em_analise'=>'Em Análise','encaminhado'=>'Encaminhado','finalizado'=>'Finalizado'][$doc->status] }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

{{-- Rodapé --}}
<div class="footer">
    SCED — Sistema de Controle de Entrada de Documentos &nbsp;|&nbsp;
    Documento confidencial — uso interno &nbsp;|&nbsp;
    Gerado automaticamente em {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
