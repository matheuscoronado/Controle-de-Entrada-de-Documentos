{{-- ============================================================
     Arquivo: resources/views/tipos/index.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Tipos de Documento')
@section('subtitle', 'Categorias de documentos aceitos no sistema')

@section('topbar-actions')
    <a href="{{ route('tipos.create') }}" class="btn-primary-sced">
        ➕ Novo Tipo
    </a>
@endsection

@section('content')
<div class="card-sced">
    <div style="overflow-x:auto;">
        <table class="tabela-sced">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Documentos</th>
                    <th>Status</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                <tr>
                    <td style="color:var(--cinza-400); font-size:13px;">{{ $tipo->id }}</td>
                    <td style="font-weight:600;">🏷️ {{ $tipo->nome }}</td>
                    <td style="color:var(--cinza-600); font-size:13px;">
                        {{ $tipo->descricao ?? '—' }}
                    </td>
                    <td>
                        <span style="font-weight:700; color:var(--azul-claro);">
                            {{ $tipo->documentos->count() }}
                        </span>
                        <span style="font-size:12px; color:var(--cinza-400);"> doc(s)</span>
                    </td>
                    <td>
                        @if($tipo->status === 'ativo')
                            <span style="background:#f0fdf4;color:#059669;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Ativo</span>
                        @else
                            <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">● Inativo</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('tipos.edit', $tipo) }}" class="btn-outline-sced">
                            ✏️ Editar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:48px; color:var(--cinza-400);">
                        <div style="font-size:32px; margin-bottom:8px;">🏷️</div>
                        Nenhum tipo cadastrado. Crie o primeiro!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


{{-- ============================================================
     SALVE O BLOCO ABAIXO COMO: resources/views/tipos/create.blade.php
     ============================================================ --}}
