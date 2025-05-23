<!-- Modais de Edição e Visualização seriam gerados dinamicamente para cada item -->
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
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $plano->nome }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor"
                                value="{{ $plano->valor }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="periodo">Período</label>
                        <select class="form-control" id="periodo" name="periodo" required>
                            <option value="mensal" {{ $plano->periodo == 'mensal' ? 'selected' : '' }}>Mensal</option>
                            <option value="trimestral" {{ $plano->periodo == 'trimestral' ? 'selected' : '' }}>Trimestral
                            </option>
                            <option value="semestral" {{ $plano->periodo == 'semestral' ? 'selected' : '' }}>Semestral
                            </option>
                            <option value="anual" {{ $plano->periodo == 'anual' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="limite_membros">Limite de Membros</label>
                        <input type="number" min="1" class="form-control" id="limite_membros" name="limite_membros"
                            value="{{ $plano->limite_membros }}" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao"
                            rows="3">{{ $plano->descricao }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="ativo-{{ $plano->id }}" name="ativo"
                                {{ $plano->ativo ? 'checked' : '' }}>
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