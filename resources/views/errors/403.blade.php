<x-errors-layout title="Acesso Negado" type="warning" :showContact=false>
    <p class="text-gray-600 error-message">
        Você não tem permissão para acessar esta página.
        Entre em contato com o administrador do sistema.
    </p>

    @slot('code')
        ERROR_403_FORBIDDEN
    @endslot
</x-errors-layout>
