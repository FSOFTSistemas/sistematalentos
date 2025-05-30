<!-- Modal de Exclusão -->
<div class="modal fade" id="modal-delete-{{ $membro->id }}" tabindex="-1" aria-labelledby="modal-delete-label-{{ $membro->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modal-delete-label-{{ $membro->id }}">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir o membro <strong>{{ $membro->nome }}</strong>?
            </div>
            <div class="modal-footer">
                <form action="{{ route('membros.destroy', $membro->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Sim, excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>