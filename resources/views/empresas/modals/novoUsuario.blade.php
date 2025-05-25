<!-- Modal para Adicionar Usuário -->
<div class="modal fade" id="modal-add-user-{{ $empresa->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Adicionar Usuário</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('empresas.add-user', $empresa->id) }}" method="POST" class="needs-validation"
                novalidate>
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">
                            Por favor, informe o nome do usuário.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">
                            Por favor, informe um email válido.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">
                            Por favor, informe uma senha.
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">Papel</label>
                        <select class="form-control select2" id="role" name="role" required>
                            <option value="admin">Administrador</option>
                            <option value="user">Usuário</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor, selecione um papel.
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>
