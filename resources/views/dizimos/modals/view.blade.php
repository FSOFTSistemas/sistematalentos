<!-- Modal de Visualização -->
<div class="modal fade" id="modal-view-{{ $dizimo->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">Detalhes do Dízimo</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <dl class="row">
                    <dt class="col-sm-4">ID:</dt>
                    <dd class="col-sm-8">{{ $dizimo->id }}</dd>

                    <dt class="col-sm-4">Membro:</dt>
                    <dd class="col-sm-8">{{ $dizimo->membro->nome ?? 'Não informado' }}</dd>

                    <dt class="col-sm-4">Valor:</dt>
                    <dd class="col-sm-8">R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</dd>

                    <dt class="col-sm-4">Data:</dt>
                    <dd class="col-sm-8">{{ $dizimo->data->format('d/m/Y') }}</dd>

                    <dt class="col-sm-4">Referência:</dt>
                    <dd class="col-sm-8">
                        @php
                            $meses = [
                                'Janeiro',
                                'Fevereiro',
                                'Março',
                                'Abril',
                                'Maio',
                                'Junho',
                                'Julho',
                                'Agosto',
                                'Setembro',
                                'Outubro',
                                'Novembro',
                                'Dezembro',
                            ];
                            $mes = isset($meses[$dizimo->mes_referencia - 1])
                                ? $meses[$dizimo->mes_referencia - 1]
                                : '';
                        @endphp
                        {{ $mes }}/{{ $dizimo->ano_referencia }}
                    </dd>

                    <dt class="col-sm-4">Observação:</dt>
                    <dd class="col-sm-8">{{ $dizimo->observacao ?? 'Nenhuma observação' }}</dd>

                    <dt class="col-sm-4">Registrado por:</dt>
                    <dd class="col-sm-8">{{ $dizimo->user->name ?? 'Sistema' }}</dd>

                    <dt class="col-sm-4">Data de Registro:</dt>
                    <dd class="col-sm-8">{{ $dizimo->created_at->format('d/m/Y H:i:s') }}</dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-toggle="modal"
                    data-target="#modal-edit-{{ $dizimo->id }}">Editar</button>
            </div>
        </div>
    </div>
</div>
