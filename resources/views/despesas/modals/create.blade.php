<!-- Modal para Nova Despesa -->
<div class="modal fade" id="modal-nova-despesa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title">Nova Despesa</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('despesas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="valor"
                                name="valor" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data">Data de Registro</label>
                                <input type="date" class="form-control" id="data" name="data"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="data_vencimento">Data de Vencimento</label>
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    <option value="">Selecione uma categoria</option>
                                    <option value="Água/Luz">Água/Luz</option>
                                    <option value="Aluguel">Aluguel</option>
                                    <option value="Material">Material</option>
                                    <option value="Manutenção">Manutenção</option>
                                    <option value="Salário">Salário</option>
                                    <option value="Evento">Evento</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="pendente">Pendente</option>
                                    <option value="paga">Pago</option>
                                    <option value="vencido">Vencido</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fornecedor">Fornecedor</label>
                        <input type="text" class="form-control" id="fornecedor" name="fornecedor">
                    </div>
                    <div class="form-group">
                        <label for="numero_documento">Número do Documento</label>
                        <input type="text" class="form-control" id="numero_documento" name="numero_documento">
                    </div>
                    <div class="form-group">
                        <label for="observacao">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
