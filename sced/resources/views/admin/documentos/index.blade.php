{{-- resources/views/admin/documentos/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Cadastro de Documentos')
@section('subtitle', 'Gerencie os tipos de documento disponíveis no sistema')

@section('topbar-actions')
    <a href="{{ route('documentos-tipo.create') }}" class="btn-primary-sced">
        ➕ Novo Documento
    </a>
@endsection

@section('content')

{{-- Mensagens --}}
@if(session('success'))
    <div class="alert-sced alert-success mb-4">✅ {{ session('success') }}</div>
@endif

{{-- Cards de resumo --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Total</div>
            <div style="font-size:28px;font-weight:700;color:var(--azul-claro);">{{ $documentos->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Obrigatórios</div>
            <div style="font-size:28px;font-weight:700;color:var(--vermelho);">{{ $documentos->where('tipo','obrigatorio')->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Opcionais</div>
            <div style="font-size:28px;font-weight:700;color:var(--ciano);">{{ $documentos->where('tipo','opcional')->count() }}</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-sced" style="padding:18px 20px;">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;color:var(--cinza-400);margin-bottom:6px;">Ativos</div>
            <div style="font-size:28px;font-weight:700;color:var(--verde);">{{ $documentos->where('status','ativo')->count() }}</div>
        </div>
    </div>
</div>

<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome do Documento</th>
                    <th>Descrição</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documentos as $doc)
                <tr>
                    <td style="color:var(--cinza-400);font-size:12px;">{{ $doc->id }}</td>

                    <td>
                        <div style="font-weight:600;">📄 {{ $doc->nome }}</div>
                    </td>

                    <td style="font-size:13px;color:var(--cinza-500);max-width:280px;">
                        {{ Str::limit($doc->descricao, 70) }}
                    </td>

                    <td>
                        @if($doc->tipo === 'obrigatorio')
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Obrigatório</span>
                        @else
                            <span style="background:#f0f9ff;color:#0369a1;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">○ Opcional</span>
                        @endif
                    </td>

                    <td>
                        @if($doc->status === 'ativo')
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        @else
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        @endif
                    </td>

                    <td style="text-align:center;">
                        <a href="{{ route('documentos-tipo.edit', $doc) }}" class="btn-outline-sced">✏️ Editar</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:48px;color:var(--cinza-400);">
                        <div style="font-size:32px;margin-bottom:8px;">📄</div>
                        Nenhum documento cadastrado ainda.
                        <a href="{{ route('documentos-tipo.create') }}" style="color:var(--azul-claro);font-weight:600;">Criar o primeiro</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection