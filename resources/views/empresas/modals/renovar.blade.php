<!-- Modal para Renovar Plano -->
        <div class="modal fade" id="modal-renovar-{{ $empresa->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">Renovar Plano</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('empresas.renovar-plano', $empresa->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> A renovação irá estender o plano atual por mais um período.
                            </div>
                            
                            <div class="form-group">
                                <label for="data_inicio_plano">Data de Início da Renovação</label>
                                <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano" value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Plano Atual</label>
                                <p class="form-control-static">
                                    {{ $empresa->plano->nome ?? 'Sem plano' }} - 
                                    R$ {{ number_format($empresa->plano->valor ?? 0, 2, ',', '.') }} 
                                    ({{ $empresa->plano->periodoFormatado ?? 'N/A' }})
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label>Validade Atual</label>
                                <p class="form-control-static">
                                    @if($empresa->data_fim_plano)
                                        {{ \Carbon\Carbon::parse($empresa->data_fim_plano)->format('d/m/Y') }}
                                        @if(\Carbon\Carbon::parse($empresa->data_fim_plano)->isPast())
                                            <span class="badge badge-danger">Vencido</span>
                                        @elseif(\Carbon\Carbon::parse($empresa->data_fim_plano)->diffInDays(now()) < 15)
                                            <span class="badge badge-warning">Próximo ao vencimento</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Renovar Plano</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>