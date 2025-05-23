<!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $empresa->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('empresas.update', $empresa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Dados da Empresa</h5>
                                    <div class="form-group">
                                        <label for="nome">Nome da Empresa/Igreja</label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $empresa->nome }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ $empresa->cnpj }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $empresa->email }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ $empresa->telefone }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="responsavel">Responsável</label>
                                        <input type="text" class="form-control" id="responsavel" name="responsavel" value="{{ $empresa->responsavel }}" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="ativo-{{ $empresa->id }}" name="ativo" {{ $empresa->ativo ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="ativo-{{ $empresa->id }}">Empresa Ativa</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Dados do Plano</h5>
                                    <div class="form-group">
                                        <label for="plano_id">Plano</label>
                                        <select class="form-control" id="plano_id" name="plano_id" required>
                                            <option value="">Selecione um plano</option>
                                            @foreach($planos ?? [] as $plano)
                                                <option value="{{ $plano->id }}" {{ $empresa->plano_id == $plano->id ? 'selected' : '' }}>
                                                    {{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }} ({{ $plano->periodoFormatado }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="data_inicio_plano">Data de Início</label>
                                        <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano" value="{{ $empresa->data_inicio_plano ? \Carbon\Carbon::parse($empresa->data_inicio_plano)->format('Y-m-d') : date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="renovar_plano-{{ $empresa->id }}" name="renovar_plano">
                                            <label class="custom-control-label" for="renovar_plano-{{ $empresa->id }}">Renovar Plano</label>
                                        </div>
                                        <small class="form-text text-muted">Marque esta opção para recalcular a data de fim do plano.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ $empresa->observacoes }}</textarea>
                                    </div>
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