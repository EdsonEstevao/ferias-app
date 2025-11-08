<x-errors-layout title="Erro Interno do Servidor" type="error" :showContact=false>
    <p class="text-gray-600 error-message">
        Ocorreu um erro interno no servidor.
        Nossa equipe já foi notificada e está trabalhando na solução.
    </p>

    @slot('code')
        ERROR_500_INTERNAL_SERVER_ERROR
    @endslot
</x-errors-layout>
