@extends('adminlte::page')

@section('title', 'Gerenciamento de Planos')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Planos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Planos de Assinatura</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-novo-plano">
                    <i class="fas fa-plus"></i> Novo Plano
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th>Período</th>
                        <th>Limite de Membros</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($planos ?? [] as $plano)
                        <tr>
                            <td>{{ $plano->id }}</td>
                            <td>{{ $plano->nome }}</td>
                            <td>R$ {{ number_format($plano->valor, 2, ',', '.') }}</td>
                            <td>{{ $plano->periodoFormatado }}</td>
                            <td>{{ $plano->limite_membros }}</td>
                            <td>
                                @if($plano->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $plano->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $plano->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('planos.destroy', $plano->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Novo Plano -->
    <div class="modal fade" id="modal-novo-plano">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Novo Plano</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('planos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nome">Nome do Plano</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="periodo">Período</label>
                            <select class="form-control" id="periodo" name="periodo" required>
                                <option value="mensal">Mensal</option>
                                <option value="trimestral">Trimestral</option>
                                <option value="semestral">Semestral</option>
                                <option value="anual">Anual</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="limite_membros">Limite de Membros</label>
                            <input type="number" min="1" class="form-control" id="limite_membros" name="limite_membros" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="ativo" name="ativo" checked>
                                <label class="custom-control-label" for="ativo">Plano Ativo</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais de Edição e Visualização seriam gerados dinamicamente para cada item -->
    @foreach($planos ?? [] as $plano)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $plano->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Detalhes do Plano</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                            
                            <dt class="col-sm-4">Empresas Usando:</dt>
                            <dd class="col-sm-8">{{ $plano->empresas->count() }}</dd>
                            
                            <dt class="col-sm-4">Data de Criação:</dt>
                            <dd class="col-sm-8">{{ $plano->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-{{ $plano->id }}">Editar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $plano->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Plano</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('planos.update', $plano->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nome">Nome do Plano</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="{{ $plano->nome }}" required>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" value="{{ $plano->valor }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="periodo">Período</label>
                                <select class="form-control" id="periodo" name="periodo" required>
                                    <option value="mensal" {{ $plano->periodo == 'mensal' ? 'selected' : '' }}>Mensal</option>
                                    <option value="trimestral" {{ $plano->periodo == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                                    <option value="semestral" {{ $plano->periodo == 'semestral' ? 'selected' : '' }}>Semestral</option>
                                    <option value="anual" {{ $plano->periodo == 'anual' ? 'selected' : '' }}>Anual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="limite_membros">Limite de Membros</label>
                                <input type="number" min="1" class="form-control" id="limite_membros" name="limite_membros" value="{{ $plano->limite_membros }}" required>
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ $plano->descricao }}</textarea>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="ativo-{{ $plano->id }}" name="ativo" {{ $plano->ativo ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="ativo-{{ $plano->id }}">Plano Ativo</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
<script>
    $(function () {
        // Inicialização específica para a tabela de planos
        $('.datatable').DataTable({
            "order": [[0, 'asc']], // Ordenar por ID (coluna 0) crescente
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }
            ],
            "dom": 'Bfrtip'
        });
        
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
