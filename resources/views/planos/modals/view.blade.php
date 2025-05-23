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
                <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal"
                    data-target="#modal-edit-{{ $plano->id }}">Editar</button>
            </div>
        </div>
    </div>
</div>