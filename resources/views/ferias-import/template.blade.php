<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            Histórico de Importações
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('ferias-import.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nova Importação
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Funcionalidade em Desenvolvimento</h5>
                            Em breve você poderá visualizar o histórico de importações realizadas.
                        </div>

                        <div class="text-center py-4">
                            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Esta funcionalidade está sendo desenvolvida.</p>
                            <a href="{{ route('ferias-import.create') }}" class="btn btn-primary">
                                <i class="fas fa-file-import"></i> Fazer Nova Importação
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
