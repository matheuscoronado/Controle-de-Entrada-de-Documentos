{{-- ============================================================
     resources/views/relatorios/pdf.blade.php
     TEMPLATE DO PDF GERADO PELO DomPDF
     ============================================================ --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório SCED - {{ now()->format('d/m/Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            line-height: 1.4;
        }

        /* Cabeçalho */
        .header {
            background: #0f2744;
            color: white;
            padding: 20px 24px;
            margin-bottom: 20px;
            border-radius: 8px 8px 0 0;
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
            margin: 0;
        }

        .header .sub {
            font-size: 9px;
            color: rgba(255,255,255,0.6);
            margin-top: 3px;
        }

        .header-info {
            text-align: right;
            font-size: 9px;
            color: rgba(255,255,255,0.7);
            line-height: 1.5;
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
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .filtros-linha {
            font-size: 9px;
            color: #475569;
        }

        /* Resumo numérico */
        .resumo {
            display: flex;
            gap: 12px;
            margin: 0 24px 20px;
            flex-wrap: wrap;
        }

        .resumo-item {
            flex: 1;
            text-align: center;
            padding: 10px 8px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .resumo-valor {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.2;
        }

        .resumo-label {
            font-size: 8px;
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
            letter-spacing: 0.5px;
            font-weight: bold;
        }

        tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .protocolo {
            font-family: monospace;
            font-size: 9px;
            color: #2563eb;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
        }

        .badge-novo        { background: #eff6ff; color: #2563eb; }
        .badge-em_analise  { background: #fffbeb; color: #d97706; }
        .badge-pendente    { background: #fef3c7; color: #92400e; }
        .badge-finalizado  { background: #f0fdf4; color: #059669; }
        .badge-desativado  { background: #f1f5f9; color: #64748b; }

        /* Rodapé */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            padding: 8px 24px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }

        .sem-dados {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
            font-size: 12px;
        }

        /* Cores dos status */
        .text-primary { color: #2563eb; }
        .text-warning { color: #d97706; }
        .text-danger { color: #dc2626; }
        .text-success { color: #059669; }

        .status-novo      { color: #2563eb; }
        .status-em_analise { color: #d97706; }
        .status-pendente   { color: #92400e; }
        .status-finalizado { color: #059669; }
        .status-desativado { color: #64748b; }
    </style>
</head>
<body>

{{-- Cabeçalho --}}
<div class="header">
    <div class="header-top">
        <div>
            <h1>📂 SCED</h1>
            <div class="sub">Sistema de Controle de Entrada de Documentos</div>
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
    <div class="filtros-title">📌 Filtros aplicados</div>
    <div class="filtros-linha">
        @if($request->status)
            Status: <strong>{{ $request->status }}</strong> &nbsp;|&nbsp;
        @endif
        @if($request->tipo_documento_id)
            Serviço: <strong>{{ \App\Models\TipoDocumento::find($request->tipo_documento_id)?->nome ?? '—' }}</strong> &nbsp;|&nbsp;
        @endif
        @if($request->data_inicio && $request->data_fim)
            Período: <strong>{{ \Carbon\Carbon::parse($request->data_inicio)->format('d/m/Y') }}</strong>
            até <strong>{{ \Carbon\Carbon::parse($request->data_fim)->format('d/m/Y') }}</strong>
        @else
            Período: <strong>Todos os registros</strong>
        @endif
        @if(!$request->status && !$request->tipo_documento_id && !$request->data_inicio)
            Sem filtros — todos os processos
        @endif
    </div>
</div>

{{-- Resumo --}}
@php
    $contadores = $documentos->groupBy('status')->map->count();
    $statusLabels = [
        'novo' => 'Novos',
        'em_analise' => 'Em Análise',
        'pendente' => 'Pendentes',
        'finalizado' => 'Finalizados',
        'desativado' => 'Desativados',
    ];
@endphp
<div class="resumo">
    @foreach($statusLabels as $status => $label)
    <div class="resumo-item">
        <div class="resumo-valor status-{{ $status }}">{{ $contadores[$status] ?? 0 }}</div>
        <div class="resumo-label">{{ $label }}</div>
    </div>
    @endforeach
    <div class="resumo-item">
        <div class="resumo-valor">{{ $documentos->count() }}</div>
        <div class="resumo-label">Total</div>
    </div>
</div>

{{-- Tabela de processos --}}
<div class="tabela-container">
    @if($documentos->isEmpty())
        <div class="sem-dados">Nenhum processo encontrado com os filtros informados.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Protocolo</th>
                <th>Serviço</th>
                <th>Solicitante</th>
                <th>Setor Destino</th>
                <th>Abertura</th>
                <th>Anexos</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $doc)
            <tr>
                <td><span class="protocolo">{{ $doc->numero_protocolo }}</span></td>
                <td>{{ $doc->tipoDocumento->nome ?? '-' }}</td>
                <td>{{ $doc->remetente }}</td>
                <td>{{ $doc->setor_destino }}</td>
                <td>{{ \Carbon\Carbon::parse($doc->created_at)->format('d/m/Y H:i') }}</td>
                <td style="text-align: center;">{{ $doc->anexos_count ?? 0 }}</td>
                <td>
                    <span class="badge badge-{{ $doc->status }}">
                        @php
                            $labels = [
                                'novo' => 'Novo',
                                'em_analise' => 'Em Análise',
                                'pendente' => 'Pendente',
                                'finalizado' => 'Finalizado',
                                'desativado' => 'Desativado',
                            ];
                        @endphp
                        {{ $labels[$doc->status] ?? $doc->status }}
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