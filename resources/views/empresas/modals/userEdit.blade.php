{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar{{ $usuario->id }}" tabindex="-1"
    aria-labelledby="modalEditarLabel{{ $usuario->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('usuarios.update', $usuario->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel{{ $usuario->id }}">
                        Editar Usuário</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $usuario->id }}" class="form-label">Nome</label>
                        <input type="text" name="name" id="name{{ $usuario->id }}" class="form-control"
                            value="{{ $usuario->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email{{ $usuario->id }}" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email{{ $usuario->id }}" class="form-control"
                            value="{{ $usuario->email }}" required>
                    </div>
                    {{-- Adicione outros campos, como seleção de roles se necessário --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar
                        alterações</button>
                </div>
            </div>
        </form>
    </div>
</div>
