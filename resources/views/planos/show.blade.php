@extends('adminlte::page')

@section('title', 'Informações do Plano')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('planos.index') }}">Planos</a></li>
    <li class="breadcrumb-item active">Informações do Plano</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detalhes do Plano</h3>
            <div class="card-tools">
                <a href="{{ route('planos.edit', $plano->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">ID:</dt>
                        <dd class="col-sm-8">{{ $plano->id }}</dd>
                        
                        <dt class="col-sm-4">Nome:</dt>
                        <dd class="col-sm-8">{{ $plano->nome }}</dd>
                        
                        <dt class="col-sm-4">Valor:</dt>
                        <dd class="col-sm-8">R$ {{ number_format($plano->valor, 2, ',', '.') }}</dd>
                        
                        <dt class="col-sm-4">Período:</dt>
                        <dd class="col-sm-8">{{ $plano->periodoFormatado }}</dd>
                        
                        <dt class="col-sm-4">Limite de Membros:</dt>
                        <dd class="col-sm-8">{{ $plano->limite_membros }}</dd>
                        
                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            @if($plano->ativo)
                                <span class="badge badge-success">Ativo</span>
                            @else
                                <span class="badge badge-danger">Inativo</span>
                            @endif
                        </dd>
                        
                        <dt class="col-sm-4">Descrição:</dt>
                        <dd class="col-sm-8">{{ $plano->descricao ?? 'Nenhuma descrição' }}</dd>
                        
                        <dt class="col-sm-4">Data de Criação:</dt>
                        <dd class="col-sm-8">{{ $plano->created_at->format('d/m/Y H:i:s') }}</dd>
                        
                        <dt class="col-sm-4">Última Atualização:</dt>
                        <dd class="col-sm-8">{{ $plano->updated_at->format('d/m/Y H:i:s') }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Empresas Usando Este Plano</h3>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Validade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plano->empresas as $empresa)
                                        <tr>
                                            <td>{{ $empresa->nome }}</td>
                                            <td>
                                                @if($empresa->data_fim_plano)
                                                    {{ \Carbon\Carbon::parse($empresa->data_fim_plano)->format('d/m/Y') }}
                                                    @if(\Carbon\Carbon::parse($empresa->data_fim_plano)->isPast())
                                                        <span class="badge badge-danger">Vencido</span>
                                                    @elseif(\Carbon\Carbon::parse($empresa->data_fim_plano)->diffInDays(now()) < 15)
                                                        <span class="badge badge-warning">Próximo ao vencimento</span>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($empresa->ativo && $empresa->planoAtivo())
                                                    <span class="badge badge-success">Ativo</span>
                                                @else
                                                    <span class="badge badge-danger">Inativo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Nenhuma empresa está usando este plano</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('planos.index') }}" class="btn btn-default">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <form action="{{ route('planos.destroy', $plano->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-delete">
                    <i class="fas fa-trash"></i> Excluir
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function () {
        // Confirmação de exclusão com SweetAlert2
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
