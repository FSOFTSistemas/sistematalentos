@extends('adminlte::page')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalMembros ?? 0 }}</h3>
                    <p>Membros Cadastrados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('membros.index') }}" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($saldoAtual ?? 0, 2, ',', '.') }}</h3>
                    <p>Saldo Atual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('caixa.index') }}" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>R$ {{ number_format($totalDizimosMes ?? 0, 2, ',', '.') }}</h3>
                    <p>Dízimos do Mês</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <a href="{{ route('dizimos.index') }}" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>R$ {{ number_format($totalDespesasMes ?? 0, 2, ',', '.') }}</h3>
                    <p>Despesas do Mês</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <a href="{{ route('despesas.index') }}" class="small-box-footer">
                    Mais informações <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Movimentações Recentes</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movimentacoesRecentes ?? [] as $movimentacao)
                                    <tr>
                                        <td>{{ $movimentacao->data?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $movimentacao->descricao }}</td>
                                        <td>
                                            @if($movimentacao->tipo == 'entrada')
                                                <span class="badge badge-success">Entrada</span>
                                            @else
                                                <span class="badge badge-danger">Saída</span>
                                            @endif
                                        </td>
                                        <td>R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aniversariantes do Mês</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($aniversariantes ?? [] as $aniversariante)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-birthday-cake text-primary mr-2"></i>
                                        {{ $aniversariante->nome }}
                                    </div>
                                    <span class="badge badge-primary">
                                        {{ $aniversariante->data_nascimento?->format('d/m') ?? '-' }}
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">
                                Nenhum aniversariante este mês
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Despesas Pendentes</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($despesasPendentes ?? [] as $despesa)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        {{ $despesa->descricao }}
                                    </div>
                                    <div>
                                        <span class="badge badge-warning">
                                            {{ $despesa->data_vencimento?->format('d/m/Y') ?? '-' }}
                                        </span>
                                        <span class="badge badge-danger ml-1">
                                            R$ {{ number_format($despesa->valor, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center">
                                Nenhuma despesa pendente
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
