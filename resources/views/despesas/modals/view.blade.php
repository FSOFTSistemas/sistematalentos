<!-- Modal de Visualização -->
<div class="modal fade" id="modal-view-{{ $despesa->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Detalhes da Despesa</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">ID:</dt>
                    <dd class="col-sm-8">{{ $despesa->id }}</dd>

                    <dt class="col-sm-4">Descrição:</dt>
                    <dd class="col-sm-8">{{ $despesa->descricao }}</dd>

                    <dt class="col-sm-4">Valor:</dt>
                    <dd class="col-sm-8">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</dd>

                    <dt class="col-sm-4">Data:</dt>
                    <dd class="col-sm-8">{{ $despesa->data ? $despesa->data->format('d/m/Y') : 'Não informado' }}</dd>

                    <dt class="col-sm-4">Vencimento:</dt>
                    <dd class="col-sm-8">
                        {{ $despesa->data_vencimento ? $despesa->data_vencimento->format('d/m/Y') : 'Não informado' }}
                    </dd>

                    <dt class="col-sm-4">Categoria:</dt>
                    <dd class="col-sm-8">{{ $despesa->categoria }}</dd>

                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        @if ($despesa->status == 'pago')
                            <span class="badge badge-success">Pago</span>
                        @elseif($despesa->status == 'pendente')
                            <span class="badge badge-warning">Pendente</span>
                        @elseif($despesa->status == 'vencido')
                            <span class="badge badge-danger">Vencido</span>
                        @else
                            <span class="badge badge-secondary">{{ $despesa->status }}</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4">Fornecedor:</dt>
                    <dd class="col-sm-8">{{ $despesa->fornecedor ?? 'Não informado' }}</dd>

                    <dt class="col-sm-4">Documento:</dt>
                    <dd class="col-sm-8">{{ $despesa->numero_documento ?? 'Não informado' }}</dd>

                    <dt class="col-sm-4">Observação:</dt>
                    <dd class="col-sm-8">{{ $despesa->observacao ?? 'Nenhuma observação' }}</dd>

                    <dt class="col-sm-4">Registrado por:</dt>
                    <dd class="col-sm-8">{{ $despesa->user->name ?? 'Sistema' }}</dd>

                    <dt class="col-sm-4">Data de Registro:</dt>
                    <dd class="col-sm-8">{{ $despesa->created_at->format('d/m/Y H:i:s') }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-toggle="modal"
                    data-target="#modal-edit-{{ $despesa->id }}">Editar</button>
            </div>
        </div>
    </div>
</div>
