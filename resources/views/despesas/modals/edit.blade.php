<!-- Modal de Edição -->
<div class="modal fade" id="modal-edit-{{ $despesa->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Editar Despesa</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('despesas.update', $despesa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao"
                            value="{{ $despesa->descricao }}" required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="valor"
                                name="valor" value="{{ $despesa->valor }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data">Data de Registro</label>
                                <input type="date" class="form-control" id="data" name="data"
                                    value="{{ $despesa->data ? $despesa->data->format('Y-m-d') : date('Y-m-d') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_vencimento">Data de Vencimento</label>
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento"
                                    value="{{ $despesa->data_vencimento ? $despesa->data_vencimento->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Água/Luz" {{ $despesa->categoria == 'Água/Luz' ? 'selected' : '' }}>
                                        Água/Luz</option>
                                    <option value="Aluguel" {{ $despesa->categoria == 'Aluguel' ? 'selected' : '' }}>
                                        Aluguel</option>
                                    <option value="Material" {{ $despesa->categoria == 'Material' ? 'selected' : '' }}>
                                        Material</option>
                                    <option value="Manutenção"
                                        {{ $despesa->categoria == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                    <option value="Salário" {{ $despesa->categoria == 'Salário' ? 'selected' : '' }}>
                                        Salário</option>
                                    <option value="Evento" {{ $despesa->categoria == 'Evento' ? 'selected' : '' }}>
                                        Evento</option>
                                    <option value="Outro" {{ $despesa->categoria == 'Outro' ? 'selected' : '' }}>Outro
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="pendente" {{ $despesa->status == 'pendente' ? 'selected' : '' }}>
                                        Pendente</option>
                                    <option value="paga" {{ $despesa->status == 'paga' ? 'selected' : '' }}>Pago
                                    </option>
                                    <option value="vencido" {{ $despesa->status == 'vencido' ? 'selected' : '' }}>
                                        Vencido</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fornecedor">Fornecedor</label>
                        <input type="text" class="form-control" id="fornecedor" name="fornecedor"
                            value="{{ $despesa->fornecedor }}">
                    </div>
                    <div class="form-group">
                        <label for="numero_documento">Número do Documento</label>
                        <input type="text" class="form-control" id="numero_documento" name="numero_documento"
                            value="{{ $despesa->numero_documento }}">
                    </div>
                    <div class="form-group">
                        <label for="observacao">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $despesa->observacao }}</textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
