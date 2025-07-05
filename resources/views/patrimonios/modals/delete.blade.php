

<div class="modal fade" id="modal-delete-{{ $patrimonio->id }}" tabindex="-1" aria-labelledby="modalDeleteLabel{{ $patrimonio->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('patrimonios.destroy', $patrimonio->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalDeleteLabel{{ $patrimonio->id }}">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir o patrimônio <strong>{{ $patrimonio->descricao }}</strong> (Nº {{ $patrimonio->numero_patrimonio }})?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Excluir</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>